<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat Trigger MySQL untuk Otomatisasi Update Saldo Rekening (Standar UKK RPL)
     */
    public function up(): void
    {
        // Hanya jalankan jika menggunakan driver MySQL / MariaDB
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Hapus trigger lama jika sudah ada
        DB::unprepared("DROP TRIGGER IF EXISTS `trg_cek_saldo_penarikan`");
        DB::unprepared("DROP TRIGGER IF EXISTS `trg_transaksi_update_saldo`");
        DB::unprepared("DROP TRIGGER IF EXISTS `trg_transaksi_after_delete`");

        // Cek kolom saver_id di tabel rekening untuk kompatibilitas skema
        $hasSaverId = Schema::hasColumn('rekening', 'saver_id');

        $whereInsert = $hasSaverId
            ? "(`id_nasabah` = NEW.id_nasabah OR (`saver_id` IS NOT NULL AND `saver_id` = NEW.id_nasabah))"
            : "`id_nasabah` = NEW.id_nasabah";

        $whereDelete = $hasSaverId
            ? "(`id_nasabah` = OLD.id_nasabah OR (`saver_id` IS NOT NULL AND `saver_id` = OLD.id_nasabah))"
            : "`id_nasabah` = OLD.id_nasabah";

        // 1. TRIGGER BEFORE INSERT: Validasi Saldo Penarikan (Pencegahan Saldo Minus di Level Database)
        DB::unprepared("
            CREATE TRIGGER `trg_cek_saldo_penarikan`
            BEFORE INSERT ON `transaksi`
            FOR EACH ROW
            BEGIN
                DECLARE current_saldo BIGINT DEFAULT 0;

                IF NEW.jenis_trans = 'penarikan' THEN
                    SELECT `saldo` INTO current_saldo 
                    FROM `rekening` 
                    WHERE {$whereInsert}
                    LIMIT 1;

                    IF (current_saldo - NEW.nominal) < 0 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Transaksi Ditolak (Trigger Database): Saldo tidak mencukupi untuk melakukan penarikan!';
                    END IF;
                END IF;
            END
        ");

        // 2. TRIGGER AFTER INSERT: Update Saldo Otomatis (Setoran bertambah, Penarikan berkurang)
        DB::unprepared("
            CREATE TRIGGER `trg_transaksi_update_saldo`
            AFTER INSERT ON `transaksi`
            FOR EACH ROW
            BEGIN
                IF NEW.jenis_trans = 'setoran' THEN
                    UPDATE `rekening`
                    SET `saldo` = `saldo` + NEW.nominal,
                        `updated_at` = NOW()
                    WHERE {$whereInsert};
                ELSEIF NEW.jenis_trans = 'penarikan' THEN
                    UPDATE `rekening`
                    SET `saldo` = `saldo` - NEW.nominal,
                        `updated_at` = NOW()
                    WHERE {$whereInsert};
                END IF;
            END
        ");

        // 3. TRIGGER AFTER DELETE: Rollback Saldo Otomatis jika Data Transaksi Dihapus
        DB::unprepared("
            CREATE TRIGGER `trg_transaksi_after_delete`
            AFTER DELETE ON `transaksi`
            FOR EACH ROW
            BEGIN
                IF OLD.jenis_trans = 'setoran' THEN
                    UPDATE `rekening`
                    SET `saldo` = `saldo` - OLD.nominal,
                        `updated_at` = NOW()
                    WHERE {$whereDelete};
                ELSEIF OLD.jenis_trans = 'penarikan' THEN
                    UPDATE `rekening`
                    SET `saldo` = `saldo` + OLD.nominal,
                        `updated_at` = NOW()
                    WHERE {$whereDelete};
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared("DROP TRIGGER IF EXISTS `trg_cek_saldo_penarikan`");
        DB::unprepared("DROP TRIGGER IF EXISTS `trg_transaksi_update_saldo`");
        DB::unprepared("DROP TRIGGER IF EXISTS `trg_transaksi_after_delete`");
    }
};
