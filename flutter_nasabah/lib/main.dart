import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

void main() {
  runApp(const BankMiniApp());
}

class BankMiniApp extends StatelessWidget {
  const BankMiniApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Nasabah | E-Teller Bank Mini',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        scaffoldBackgroundColor: const Color(0xFFF4F7FE),
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF2563EB),
          primary: const Color(0xFF2563EB),
          surface: Colors.white,
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: Colors.white,
          foregroundColor: Color(0xFF0F172A),
          elevation: 0,
          centerTitle: false,
        ),
        cardTheme: const CardThemeData(
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.all(Radius.circular(16)),
            side: BorderSide(color: Color(0xFFE2E8F0)),
          ),
          color: Colors.white,
        ),
        inputDecorationTheme: InputDecorationTheme(
          filled: true,
          fillColor: Colors.white,
          contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(color: Color(0xFF2563EB), width: 1.5),
          ),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            backgroundColor: const Color(0xFF2563EB),
            foregroundColor: Colors.white,
            padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
            elevation: 0,
            textStyle: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w700,
            ),
          ),
        ),
        bottomNavigationBarTheme: const BottomNavigationBarThemeData(
          backgroundColor: Colors.white,
          selectedItemColor: Color(0xFF2563EB),
          unselectedItemColor: Color(0xFF64748B),
          selectedLabelStyle: TextStyle(fontSize: 11, fontWeight: FontWeight.w700),
          unselectedLabelStyle: TextStyle(fontSize: 11, fontWeight: FontWeight.w500),
          type: BottomNavigationBarType.fixed,
          elevation: 8,
        ),
      ),
      home: const LoginScreen(),
    );
  }
}

// Global App Config (IP Server Laravel)
class ApiConfig {
  static String baseUrl = 'https://skansabaewallet.web.id/api/nasabah';
}

// ---------------------------------------------------------------------------
// 1. HALAMAN LOGIN NASABAH
// ---------------------------------------------------------------------------
class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  bool _isLoading = false;
  bool _obscurePassword = true;
  String? _errorMessage;

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _handleLogin() async {
    final username = _usernameController.text.trim();
    final password = _passwordController.text.trim();

    if (username.isEmpty || password.isEmpty) {
      setState(() {
        _errorMessage = 'Silakan masukkan Username/Nama dan Password.';
      });
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final response = await http
          .post(
            Uri.parse('${ApiConfig.baseUrl}/login'),
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: jsonEncode({
              'username': username,
              'password': password,
            }),
          )
          .timeout(const Duration(seconds: 10));

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['success'] == true) {
        final nasabahData = data['data'];
        if (mounted) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (context) => DashboardScreen(nasabah: nasabahData),
            ),
          );
        }
      } else {
        setState(() {
          _errorMessage = data['message'] ?? 'Login gagal. Periksa kredensial Anda.';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Gagal terhubung ke server backend ($e). Pastikan server Laravel sedang aktif.';
      });
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  void _showLupaSandiDialog() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => const _LupaSandiOtpDialog(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                // Header Brand Modern dengan Logo Bank Mini
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 20),
                  decoration: BoxDecoration(
                    color: const Color(0xFF2563EB),
                    borderRadius: BorderRadius.circular(18),
                    boxShadow: const [
                      BoxShadow(
                        color: Color(0x262563EB),
                        blurRadius: 16,
                        offset: Offset(0, 6),
                      ),
                    ],
                  ),
                  child: Column(
                    children: [
                      Container(
                        width: 72,
                        height: 72,
                        padding: const EdgeInsets.all(6),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          boxShadow: const [
                            BoxShadow(
                              color: Color(0x20000000),
                              blurRadius: 10,
                              offset: Offset(0, 4),
                            ),
                          ],
                        ),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(10),
                          child: Image.asset(
                            'assets/images/logo_bank_mini.png',
                            fit: BoxFit.contain,
                            errorBuilder: (_, __, ___) => Image.network(
                              'https://skansabaewallet.web.id/images/logo_bank_mini.png',
                              fit: BoxFit.contain,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 12),
                      const Text(
                        'E-TELLER BANK MINI SEKOLAH',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w800,
                          color: Colors.white,
                          letterSpacing: 0.5,
                        ),
                      ),
                      const SizedBox(height: 4),
                      const Text(
                        'Portal Nasabah Siswa',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w500,
                          color: Color(0xFFDBEAFE),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 24),

                // Card Login
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(22),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Selamat Datang!',
                          style: TextStyle(
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                            color: Color(0xFF0F172A),
                          ),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Silakan masukkan kredensial akun Anda untuk masuk.',
                          style: TextStyle(
                            fontSize: 13,
                            color: Color(0xFF64748B),
                          ),
                        ),
                        const SizedBox(height: 20),

                        if (_errorMessage != null)
                          Container(
                            padding: const EdgeInsets.all(12),
                            margin: const EdgeInsets.only(bottom: 16),
                            decoration: BoxDecoration(
                              color: const Color(0xFFFEF2F2),
                              borderRadius: BorderRadius.circular(10),
                              border: Border.all(color: const Color(0xFFFECACA)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.info_outline, size: 18, color: Color(0xFFDC2626)),
                                const SizedBox(width: 8),
                                Expanded(
                                  child: Text(
                                    _errorMessage!,
                                    style: const TextStyle(
                                      color: Color(0xFFDC2626),
                                      fontSize: 12,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),

                        // Input Username / Nama
                        const Text(
                          'Username / Nama Siswa',
                          style: TextStyle(
                            fontSize: 13,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF1E293B),
                          ),
                        ),
                        const SizedBox(height: 6),
                        TextField(
                          controller: _usernameController,
                          decoration: const InputDecoration(
                            hintText: 'Masukkan Username atau Nama Anda',
                          ),
                        ),
                        const SizedBox(height: 16),

                        // Input Password
                        const Text(
                          'Password',
                          style: TextStyle(
                            fontSize: 13,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF1E293B),
                          ),
                        ),
                        const SizedBox(height: 6),
                        TextField(
                          controller: _passwordController,
                          obscureText: _obscurePassword,
                          decoration: InputDecoration(
                            hintText: 'Masukkan Password Anda',
                            suffixIcon: IconButton(
                              icon: Icon(
                                _obscurePassword ? Icons.visibility_off : Icons.visibility,
                                color: const Color(0xFF64748B),
                                size: 20,
                              ),
                              onPressed: () {
                                setState(() {
                                  _obscurePassword = !_obscurePassword;
                                });
                              },
                            ),
                          ),
                        ),
                        const SizedBox(height: 22),

                        // Tombol Submit Login
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: _isLoading ? null : _handleLogin,
                            child: _isLoading
                                ? const SizedBox(
                                    height: 20,
                                    width: 20,
                                    child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                                  )
                                : const Text('Masuk Sistem'),
                          ),
                        ),

                        const SizedBox(height: 16),
                        Center(
                          child: TextButton(
                            onPressed: _showLupaSandiDialog,
                            child: const Text(
                              'Lupa Sandi?',
                              style: TextStyle(
                                color: Color(0xFF2563EB),
                                fontWeight: FontWeight.w700,
                                fontSize: 13,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 16),

                // Footer
                const Text(
                  'Sistem Informasi E-Teller Bank Mini Sekolah\nUKK RPL 2026/2027',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 11,
                    color: Color(0xFF64748B),
                    height: 1.4,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// 2. HALAMAN UTAMA DASHBOARD NASABAH (3 TABS DENGAN BOTTOM NAVIGATION)
// ---------------------------------------------------------------------------
class DashboardScreen extends StatefulWidget {
  final Map<String, dynamic> nasabah;

  const DashboardScreen({super.key, required this.nasabah});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _currentIndex = 0; // 0: Dashboard, 1: Perjanjian, 2: Laporan & Target, 3: Profil & Pengaduan
  late Map<String, dynamic> _nasabahData;
  List<dynamic> _mutasiList = [];
  bool _isLoading = true;
  bool _isRefreshing = false;
  Timer? _statusPollingTimer;

  // Filter & Pencarian Mutasi (Tab 0)
  String _filterMutasi = 'all'; // 'all', 'setoran', 'penarikan'
  final TextEditingController _searchMutasiController = TextEditingController();
  String _mutasiSearchQuery = '';

  // Perjanjian Penarikan H-1 (Tab 1)
  List<dynamic> _perjanjianList = [];
  final TextEditingController _pjNominalCtrl = TextEditingController();
  final TextEditingController _pjAlasanCtrl = TextEditingController();
  DateTime _pjTanggalRencana = DateTime.now().add(const Duration(days: 1));
  bool _isSubmittingPerjanjian = false;

  // Antrean Loket
  bool _isTakingAntrean = false;

  // Laporan Bulanan (Tab 1)
  int _selectedBulan = DateTime.now().month;
  int _selectedTahun = DateTime.now().year;
  bool _isLoadingLaporan = false;
  Map<String, dynamic>? _laporanBulananData;

  // Target Tabungan (Tab 1)
  Map<String, dynamic>? _targetTabunganData;

  // Profil & Pengaduan (Tab 2)
  Map<String, dynamic>? _profilData;
  List<dynamic> _pengaduanList = [];

  // Form Ganti Password (Tab 2)
  final TextEditingController _sandiLamaCtrl = TextEditingController();
  final TextEditingController _sandiBaruCtrl = TextEditingController();
  final TextEditingController _sandiKonfCtrl = TextEditingController();
  bool _isUpdatingPassword = false;
  bool _obscureSandiLama = true;
  bool _obscureSandiBaru = true;
  bool _obscureSandiKonf = true;

  final List<String> _namaBulanList = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  dynamic get _nasabahId => _nasabahData['nasabah_id'] ?? _nasabahData['id'];
  dynamic get _userId => _nasabahData['user_id'] ?? _nasabahData['id'];

  List<dynamic> get _filteredMutasiList {
    return _mutasiList.where((item) {
      final jenis = (item['jenis_trans'] ?? '').toString().toLowerCase();
      final noTrans = (item['no_transaksi'] ?? '').toString().toLowerCase();
      final tgl = (item['tanggal'] ?? '').toString().toLowerCase();
      final ket = (item['keterangan'] ?? '').toString().toLowerCase();
      final nominal = (item['nominal'] ?? '').toString();

      final matchesType = _filterMutasi == 'all' || jenis == _filterMutasi;
      final q = _mutasiSearchQuery.toLowerCase().trim();
      final matchesSearch = q.isEmpty ||
          noTrans.contains(q) ||
          tgl.contains(q) ||
          ket.contains(q) ||
          nominal.contains(q);

      return matchesType && matchesSearch;
    }).toList();
  }

  @override
  void initState() {
    super.initState();
    _nasabahData = widget.nasabah;
    _perjanjianList = _nasabahData['perjanjian_list'] ?? [];
    _fetchDashboardData();

    // Auto sync status teller & antrean real-time setiap 4 detik (sama dengan web)
    _statusPollingTimer = Timer.periodic(const Duration(seconds: 4), (_) {
      if (mounted && !_isLoading && !_isRefreshing) {
        _fetchStatusSilent();
      }
    });
  }

  @override
  void dispose() {
    _statusPollingTimer?.cancel();
    _searchMutasiController.dispose();
    _sandiLamaCtrl.dispose();
    _sandiBaruCtrl.dispose();
    _sandiKonfCtrl.dispose();
    _pjNominalCtrl.dispose();
    _pjAlasanCtrl.dispose();
    super.dispose();
  }

  Future<void> _fetchStatusSilent() async {
    try {
      // 1. Sinkronisasi real-time saldo, antrean loket, status teller, target & pengaduan
      final res = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/dashboard/$_nasabahId?bulan=$_selectedBulan&tahun=$_selectedTahun'))
          .timeout(const Duration(seconds: 4));
      if (res.statusCode == 200 && mounted) {
        final dataDash = jsonDecode(res.body);
        if (dataDash['success'] == true && dataDash['data'] != null) {
          setState(() {
            _nasabahData = dataDash['data'];
            _targetTabunganData = dataDash['data']['target_tabungan'];
            _laporanBulananData = dataDash['data']['laporan_bulanan'];
            _profilData = dataDash['data']['profil'];
            _pengaduanList = dataDash['data']['pengaduan_list'] ?? [];
            _perjanjianList = dataDash['data']['perjanjian_list'] ?? [];
          });
        }
      }

      // 2. Sinkronisasi real-time riwayat transaksi mutasi
      final resMutasi = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/mutasi/$_nasabahId'))
          .timeout(const Duration(seconds: 4));
      if (resMutasi.statusCode == 200 && mounted) {
        final dataMutasi = jsonDecode(resMutasi.body);
        if (dataMutasi['success'] == true && dataMutasi['data'] != null) {
          setState(() {
            _mutasiList = dataMutasi['data'] ?? [];
          });
        }
      }
    } catch (_) {}
  }

  Future<void> _fetchDashboardData() async {
    try {
      final resDash = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/dashboard/$_nasabahId?bulan=$_selectedBulan&tahun=$_selectedTahun'))
          .timeout(const Duration(seconds: 8));

      if (resDash.statusCode == 200) {
        final dataDash = jsonDecode(resDash.body);
        if (dataDash['success'] == true && dataDash['data'] != null) {
          setState(() {
            _nasabahData = dataDash['data'];
            _targetTabunganData = dataDash['data']['target_tabungan'];
            _laporanBulananData = dataDash['data']['laporan_bulanan'];
            _profilData = dataDash['data']['profil'];
            _pengaduanList = dataDash['data']['pengaduan_list'] ?? [];
            _perjanjianList = dataDash['data']['perjanjian_list'] ?? [];
          });
        }
      }

      final resMutasi = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/mutasi/$_nasabahId'))
          .timeout(const Duration(seconds: 8));

      if (resMutasi.statusCode == 200) {
        final dataMutasi = jsonDecode(resMutasi.body);
        if (dataMutasi['success'] == true) {
          setState(() {
            _mutasiList = dataMutasi['data'] ?? [];
          });
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal memperbarui data: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
          _isRefreshing = false;
        });
      }
    }
  }

  Future<void> _fetchLaporanBulanan() async {
    setState(() => _isLoadingLaporan = true);
    try {
      final res = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/laporan-bulanan/$_nasabahId?bulan=$_selectedBulan&tahun=$_selectedTahun'),
      ).timeout(const Duration(seconds: 6));

      if (res.statusCode == 200) {
        final data = jsonDecode(res.body);
        if (data['success'] == true) {
          setState(() {
            _laporanBulananData = data['data'];
          });
        }
      }
    } catch (_) {}
    if (mounted) {
      setState(() => _isLoadingLaporan = false);
    }
  }

  // ACTION: AMBIL ANTREAN LOKET
  Future<void> _handleAmbilAntrean() async {
    setState(() => _isTakingAntrean = true);
    try {
      final res = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/ambil-antrean'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'user_id': _userId,
          'nasabah_id': _nasabahId,
        }),
      ).timeout(const Duration(seconds: 8));

      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['success'] == true) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFF16A34A),
              content: Text(data['message'] ?? 'Nomor antrean berhasil diambil!'),
            ),
          );
          _fetchDashboardData();
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFFDC2626),
              content: Text(data['message'] ?? 'Gagal mengambil nomor antrean.'),
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: const Color(0xFFDC2626),
            content: Text('Terjadi kesalahan antrean: $e'),
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isTakingAntrean = false);
      }
    }
  }

  // ACTION: AJUKAN PERJANJIAN PENARIKAN (> 1 JUTA)
  Future<void> _handleAjukanPerjanjian() async {
    final rawSaldo = _nasabahData['saldo'] ?? 0;
    final saldo = (rawSaldo is num) ? rawSaldo.toInt() : (int.tryParse(rawSaldo.toString()) ?? 0);
    final maxTarik = saldo > 10000 ? saldo - 10000 : 0;
    final nominalStr = _pjNominalCtrl.text.replaceAll(RegExp(r'[^0-9]'), '');
    final nominal = int.tryParse(nominalStr) ?? 0;
    final alasan = _pjAlasanCtrl.text.trim();

    if (nominal <= 1000000) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          backgroundColor: Color(0xFFDC2626),
          content: Text('Nominal penarikan wajib di atas Rp 1.000.000 (minimal Rp 1.000.001).'),
        ),
      );
      return;
    }

    if (nominal > maxTarik) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          backgroundColor: const Color(0xFFDC2626),
          content: Text('Saldo tidak mencukupi. Penarikan harus menyisakan minimal Rp 10.000. Maksimal tarik: ${_formatRupiah(maxTarik)}'),
        ),
      );
      return;
    }

    if (alasan.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          backgroundColor: Color(0xFFDC2626),
          content: Text('Harap masukkan keperluan / alasan penarikan dana.'),
        ),
      );
      return;
    }

    final tglStr = '${_pjTanggalRencana.year}-${_pjTanggalRencana.month.toString().padLeft(2, '0')}-${_pjTanggalRencana.day.toString().padLeft(2, '0')}';
    final tglDisplay = '${_pjTanggalRencana.day.toString().padLeft(2, '0')} ${_namaBulanList[_pjTanggalRencana.month - 1]} ${_pjTanggalRencana.year}';

    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Konfirmasi Pengajuan', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
        content: Text(
          'Ajukan permohonan perjanjian penarikan saldo sebesar ${_formatRupiah(nominal)} untuk rencana tanggal $tglDisplay ke pihak Teller Bank Mini?',
          style: const TextStyle(fontSize: 13, color: Color(0xFF475569)),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Batal', style: TextStyle(color: Color(0xFF64748B), fontWeight: FontWeight.w700)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF2563EB),
              foregroundColor: Colors.white,
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
            ),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Ya, Ajukan', style: TextStyle(fontWeight: FontWeight.w700)),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    setState(() => _isSubmittingPerjanjian = true);

    try {
      final res = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/perjanjian'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'user_id': _userId,
          'nominal': nominal,
          'tanggal_rencana': tglStr,
          'alasan': alasan,
        }),
      ).timeout(const Duration(seconds: 10));

      final data = jsonDecode(res.body);

      if (res.statusCode == 200 && data['success'] == true) {
        _pjNominalCtrl.clear();
        _pjAlasanCtrl.clear();
        _pjTanggalRencana = DateTime.now().add(const Duration(days: 1));
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFF16A34A),
              content: Text(data['message'] ?? 'Permohonan perjanjian penarikan berhasil diajukan ke Teller.'),
            ),
          );
        }
        await _fetchDashboardData();
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFFDC2626),
              content: Text(data['message'] ?? 'Gagal mengajukan permohonan perjanjian.'),
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: const Color(0xFFDC2626),
            content: Text('Terjadi kesalahan koneksi: $e'),
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isSubmittingPerjanjian = false);
      }
    }
  }

  // ACTION: TARGET TABUNGAN DIALOG
  void _showTargetDialog({bool isEdit = false}) {
    final judulCtrl = TextEditingController(text: isEdit ? (_targetTabunganData?['judul_target'] ?? '') : '');
    final nominalCtrl = TextEditingController(text: isEdit ? (_targetTabunganData?['nominal_target']?.toString() ?? '') : '');
    final tglCtrl = TextEditingController(text: isEdit ? (_targetTabunganData?['target_tanggal'] ?? '') : '');
    final ketCtrl = TextEditingController(text: isEdit ? (_targetTabunganData?['keterangan'] ?? '') : '');
    bool isSaving = false;

    showDialog(
      context: context,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
              title: Text(
                isEdit ? 'Perbarui Target Tabungan' : 'Pasang Target Tabungan Baru',
                style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
              ),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Nama Target / Impian', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: judulCtrl,
                      decoration: const InputDecoration(hintText: 'Contoh: Beli Laptop, Biaya PKL, dll'),
                    ),
                    const SizedBox(height: 12),
                    const Text('Nominal Target (Rp)', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: nominalCtrl,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(hintText: 'Contoh: 1500000'),
                    ),
                    const SizedBox(height: 12),
                    const Text('Target Tercapai (Tanggal)', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: tglCtrl,
                      readOnly: true,
                      decoration: InputDecoration(
                        hintText: 'Pilih Tanggal Target',
                        suffixIcon: IconButton(
                          icon: const Icon(Icons.calendar_month, color: Color(0xFF2563EB)),
                          onPressed: () async {
                            final picked = await showDatePicker(
                              context: context,
                              initialDate: DateTime.now().add(const Duration(days: 60)),
                              firstDate: DateTime.now(),
                              lastDate: DateTime(2035),
                            );
                            if (picked != null) {
                              setDialogState(() {
                                tglCtrl.text = "${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}";
                              });
                            }
                          },
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),
                    const Text('Catatan / Keterangan (Opsional)', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: ketCtrl,
                      maxLines: 2,
                      decoration: const InputDecoration(hintText: 'Contoh: Tabungan untuk modal ujian sertifikasi'),
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(ctx),
                  child: const Text('Batal'),
                ),
                ElevatedButton(
                  onPressed: isSaving
                      ? null
                      : () async {
                          final judul = judulCtrl.text.trim();
                          final nom = int.tryParse(nominalCtrl.text.trim()) ?? 0;
                          if (judul.isEmpty || nom < 10000) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Harap isi judul dan nominal minimal Rp 10.000')),
                            );
                            return;
                          }

                          final messenger = ScaffoldMessenger.of(context);
                          final navigator = Navigator.of(ctx);
                          setDialogState(() => isSaving = true);
                          try {
                            final res = await http.post(
                              Uri.parse('${ApiConfig.baseUrl}/target-tabungan'),
                              headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                              },
                              body: jsonEncode({
                                'user_id': _userId,
                                'nasabah_id': _nasabahId,
                                'judul_target': judul,
                                'nominal_target': nom,
                                'target_tanggal': tglCtrl.text.isNotEmpty ? tglCtrl.text : null,
                                'keterangan': ketCtrl.text.isNotEmpty ? ketCtrl.text : null,
                              }),
                            );
                            final data = jsonDecode(res.body);
                            if (res.statusCode == 200 && data['success'] == true) {
                              navigator.pop();
                              messenger.showSnackBar(
                                SnackBar(
                                  backgroundColor: const Color(0xFF16A34A),
                                  content: Text(data['message'] ?? 'Target tabungan berhasil disimpan!'),
                                ),
                              );
                              _fetchDashboardData();
                            }
                          } catch (e) {
                            messenger.showSnackBar(
                              SnackBar(content: Text('Gagal menyimpan target: $e')),
                            );
                          } finally {
                            setDialogState(() => isSaving = false);
                          }
                        },
                  child: isSaving
                      ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                      : const Text('Simpan Target'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  Future<void> _handleHapusTarget() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Hapus Target Tabungan?'),
        content: const Text('Apakah Anda yakin ingin menghapus target tabungan impian ini?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Batal')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFDC2626)),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirm == true) {
      try {
        final res = await http.post(
          Uri.parse('${ApiConfig.baseUrl}/target-tabungan/delete'),
          headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
          body: jsonEncode({'user_id': _userId, 'nasabah_id': _nasabahId}),
        );
        final data = jsonDecode(res.body);
        if (data['success'] == true && mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFF16A34A),
              content: Text(data['message'] ?? 'Target tabungan berhasil dihapus.'),
            ),
          );
          _fetchDashboardData();
        }
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal menghapus target: $e')));
        }
      }
    }
  }

  // ACTION: PENGADUAN NASABAH
  void _showPengaduanDialog() {
    String selectedKategori = 'Layanan Teller';
    final judulCtrl = TextEditingController();
    final pesanCtrl = TextEditingController();
    bool isSending = false;

    showDialog(
      context: context,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
              title: const Text('Buat Pengaduan Nasabah', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 18)),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Kategori Pengaduan', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      decoration: BoxDecoration(
                        border: Border.all(color: const Color(0xFFCBD5E1)),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          value: selectedKategori,
                          isExpanded: true,
                          items: const [
                            DropdownMenuItem(value: 'Layanan Teller', child: Text('Pelayanan Loket Teller')),
                            DropdownMenuItem(value: 'Masalah Saldo', child: Text('Masalah Saldo Rekening')),
                            DropdownMenuItem(value: 'Transaksi', child: Text('Transaksi / Mutasi Gagal')),
                            DropdownMenuItem(value: 'Sistem & Aplikasi', child: Text('Kendala Sistem & Aplikasi')),
                            DropdownMenuItem(value: 'Lain-lain', child: Text('Lain-lain')),
                          ],
                          onChanged: (val) {
                            if (val != null) setDialogState(() => selectedKategori = val);
                          },
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),
                    const Text('Judul Pengaduan', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: judulCtrl,
                      decoration: const InputDecoration(hintText: 'Ringkasan singkat kendala Anda'),
                    ),
                    const SizedBox(height: 12),
                    const Text('Detail Keluhan / Pesan', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: pesanCtrl,
                      maxLines: 4,
                      decoration: const InputDecoration(hintText: 'Tuliskan secara jelas kendala atau masukan Anda...'),
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Batal')),
                ElevatedButton(
                  onPressed: isSending
                      ? null
                      : () async {
                          final jdl = judulCtrl.text.trim();
                          final psn = pesanCtrl.text.trim();
                          if (jdl.isEmpty || psn.isEmpty) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Judul dan pesan pengaduan wajib diisi.')),
                            );
                            return;
                          }

                          final messenger = ScaffoldMessenger.of(context);
                          final navigator = Navigator.of(ctx);
                          setDialogState(() => isSending = true);
                          try {
                            final res = await http.post(
                              Uri.parse('${ApiConfig.baseUrl}/pengaduan'),
                              headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
                              body: jsonEncode({
                                'user_id': _userId,
                                'nasabah_id': _nasabahId,
                                'kategori': selectedKategori,
                                'judul': jdl,
                                'isi_pesan': psn,
                              }),
                            );
                            final data = jsonDecode(res.body);
                            if (res.statusCode == 200 && data['success'] == true) {
                              navigator.pop();
                              messenger.showSnackBar(
                                SnackBar(
                                  backgroundColor: const Color(0xFF16A34A),
                                  content: Text(data['message'] ?? 'Pengaduan berhasil dikirim!'),
                                ),
                              );
                              _fetchDashboardData();
                            }
                          } catch (e) {
                            messenger.showSnackBar(
                              SnackBar(content: Text('Gagal mengirim pengaduan: $e')),
                            );
                          } finally {
                            setDialogState(() => isSending = false);
                          }
                        },
                  child: isSending
                      ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                      : const Text('Kirim Pengaduan'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  // ACTION: GANTI PASSWORD
  Future<void> _handleGantiPassword() async {
    final sandiLama = _sandiLamaCtrl.text.trim();
    final sandiBaru = _sandiBaruCtrl.text.trim();
    final sandiKonf = _sandiKonfCtrl.text.trim();

    if (sandiLama.isEmpty || sandiBaru.isEmpty || sandiKonf.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Seluruh kolom kata sandi wajib diisi.')),
      );
      return;
    }

    if (sandiBaru.length < 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Kata sandi baru minimal 6 karakter.')),
      );
      return;
    }

    if (sandiBaru != sandiKonf) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Konfirmasi kata sandi baru tidak sesuai.')),
      );
      return;
    }

    setState(() => _isUpdatingPassword = true);

    try {
      final res = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/ganti-password'),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: jsonEncode({
          'user_id': _userId,
          'nasabah_id': _nasabahId,
          'sandi_lama': sandiLama,
          'sandi_baru': sandiBaru,
        }),
      );

      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['success'] == true) {
        if (mounted) {
          _sandiLamaCtrl.clear();
          _sandiBaruCtrl.clear();
          _sandiKonfCtrl.clear();
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFF16A34A),
              content: Text(data['message'] ?? 'Kata sandi berhasil diperbarui!'),
            ),
          );
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFFDC2626),
              content: Text(data['message'] ?? 'Kata sandi lama tidak cocok.'),
            ),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Terjadi kesalahan: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isUpdatingPassword = false);
      }
    }
  }

  // HELPER FORMAT RUPIAH
  String _formatRupiah(num number) {
    final str = number.toStringAsFixed(0);
    final reg = RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))');
    final result = str.replaceAllMapped(reg, (Match m) => '${m[1]}.');
    return 'Rp $result';
  }

  // MODAL QR CODE BESAR
  void _showQrModal() {
    final noRek = _nasabahData['no_rekening'] ?? _nasabahData['account_number'] ?? '-';
    final nama = _nasabahData['nama_siswa'] ?? _nasabahData['student_name'] ?? '-';
    final kelas = _nasabahData['kelas'] ?? _nasabahData['class_grade'] ?? '-';
    final nis = _nasabahData['nis'] ?? '-';
    final qrUrl = _nasabahData['qr_url'] ??
        'https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data=$noRek';

    showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Kode QR Kartu Digital',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                        color: Color(0xFF0F172A),
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, size: 20),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
                const SizedBox(height: 6),
                const Text(
                  'Tunjukkan Kode QR ini kepada Teller Loket Bank Mini Sekolah untuk pemindaian otomatis.',
                  textAlign: TextAlign.center,
                  style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                ),
                const SizedBox(height: 16),

                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFBFDBFE), width: 2),
                    boxShadow: const [
                      BoxShadow(
                        color: Color(0x142563EB),
                        blurRadius: 16,
                        offset: Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Image.network(
                    qrUrl,
                    width: 190,
                    height: 190,
                    loadingBuilder: (context, child, loadingProgress) {
                      if (loadingProgress == null) return child;
                      return const SizedBox(
                        width: 190,
                        height: 190,
                        child: Center(
                          child: CircularProgressIndicator(strokeWidth: 2),
                        ),
                      );
                    },
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        width: 190,
                        height: 190,
                        color: const Color(0xFFF1F5F9),
                        child: Center(
                          child: Column(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              const Icon(Icons.qr_code, size: 64, color: Color(0xFF2563EB)),
                              const SizedBox(height: 6),
                              Text(
                                noRek,
                                style: const TextStyle(
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF2563EB),
                                ),
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(height: 16),

                Text(
                  'No. Rek: $noRek',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF2563EB),
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  nama,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w700,
                    color: Color(0xFF0F172A),
                  ),
                ),
                Text(
                  '$kelas (NIS: $nis)',
                  style: const TextStyle(
                    fontSize: 12,
                    color: Color(0xFF64748B),
                  ),
                ),
                const SizedBox(height: 18),

                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () => Navigator.pop(context),
                    child: const Text('Tutup Kode QR'),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _handleLogout() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Konfirmasi Keluar'),
        content: const Text('Apakah Anda yakin ingin keluar dari akun nasabah?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFEF4444)),
            onPressed: () {
              Navigator.pop(context);
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(builder: (context) => const LoginScreen()),
              );
            },
            child: const Text('Keluar'),
          ),
        ],
      ),
    );
  }

  // -------------------------------------------------------------------------
  // BUILD TAB 0: DASHBOARD (PERSIS SEPERTI NASABAH DI WEB)
  // -------------------------------------------------------------------------
  Widget _buildDashboardTab() {
    final nama = _nasabahData['nama_siswa'] ?? _nasabahData['student_name'] ?? 'Siswa';
    final noRek = _nasabahData['no_rekening'] ?? _nasabahData['account_number'] ?? '-';
    final kelas = _nasabahData['kelas'] ?? _nasabahData['class_grade'] ?? '-';
    final nis = _nasabahData['nis'] ?? '-';
    final saldo = _nasabahData['saldo'] ?? 0;
    final isAktif = (_nasabahData['status_rekening'] ?? '') == 'aktif' || _nasabahData['is_aktif'] == true;
    final antrean = _nasabahData['antrean'] as Map<String, dynamic>?;

    final sedangDilayani = antrean?['sedang_dilayani'] ?? '-';
    final totalMenunggu = antrean?['total_menunggu'] ?? 0;
    final nomorSaya = antrean?['nomor_saya'];
    final statusSaya = antrean?['status_saya'];
    final sisaAntrean = antrean?['sisa_antrean'] ?? 0;

    final qrThumbUrl = _nasabahData['qr_url'] ??
        'https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=5&data=$noRek';

    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // CARD 1: IDENTITAS PENABUNG (Persis seperti kartu 1 di Web)
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'IDENTITAS PENABUNG',
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                      color: Color(0xFF64748B),
                      letterSpacing: 0.5,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    nama,
                    style: const TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.w800,
                      color: Color(0xFF0F172A),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Jurusan: $kelas • NIS: $nis',
                    style: const TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: Color(0xFF64748B),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 10),

          // CARD 2: NOMOR REKENING NASABAH (Persis seperti kartu 2 di Web dengan mini QR code)
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'NOMOR REKENING NASABAH',
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF64748B),
                            letterSpacing: 0.5,
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          isAktif ? noRek : 'Belum Aktif',
                          style: TextStyle(
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                            color: isAktif ? const Color(0xFF2563EB) : const Color(0xFFDC2626),
                            fontFamily: 'monospace',
                          ),
                        ),
                        const SizedBox(height: 8),
                        if (isAktif)
                          InkWell(
                            onTap: _showQrModal,
                            borderRadius: BorderRadius.circular(8),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                              decoration: BoxDecoration(
                                color: const Color(0xFFEFF6FF),
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: const Color(0xFFBFDBFE)),
                              ),
                              child: const Text(
                                'Lihat Kode QR',
                                style: TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF2563EB),
                                ),
                              ),
                            ),
                          )
                        else
                          const Text(
                            'Menunggu aktivasi rekening',
                            style: TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                          ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 10),
                  // MINI QR CODE PREVIEW (Seperti di web)
                  InkWell(
                    onTap: isAktif ? _showQrModal : null,
                    borderRadius: BorderRadius.circular(8),
                    child: Container(
                      width: 64,
                      height: 64,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      padding: const EdgeInsets.all(3),
                      child: isAktif
                          ? Image.network(
                              qrThumbUrl,
                              errorBuilder: (_, __, ___) => const Icon(Icons.qr_code, size: 36, color: Color(0xFF2563EB)),
                            )
                          : const Center(
                              child: Text(
                                'QR Belum\nAda',
                                textAlign: TextAlign.center,
                                style: TextStyle(fontSize: 9, color: Color(0xFF94A3B8)),
                              ),
                            ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 10),

          // CARD 3: SALDO TABUNGAN REAL-TIME (Persis seperti kartu 3 di Web)
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'SALDO TABUNGAN REAL-TIME',
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                      color: Color(0xFF64748B),
                      letterSpacing: 0.5,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    _formatRupiah(saldo),
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: isAktif ? const Color(0xFF16A34A) : const Color(0xFF94A3B8),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    isAktif ? 'Status: Rekening Aktif' : 'Status: Rekening Belum Aktif',
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                      color: isAktif ? const Color(0xFF16A34A) : const Color(0xFFDC2626),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),

          // BANNER INFO KETENTUAN PENARIKAN & JANJI TARIK (> 1 JT) (Persis seperti di Web)
          Card(
            clipBehavior: Clip.antiAlias,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFBFDBFE)),
            ),
            child: Container(
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [Color(0xFFF8FAFC), Color(0xFFEFF6FF)],
                ),
                border: Border(left: BorderSide(color: Color(0xFF2563EB), width: 5)),
              ),
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(
                          color: const Color(0xFF2563EB),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: const Text(
                          'INFO PENARIKAN',
                          style: TextStyle(
                            fontSize: 10,
                            fontWeight: FontWeight.w800,
                            color: Colors.white,
                            letterSpacing: 0.5,
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      const Text(
                        'Aturan Kas Teller',
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                          color: Color(0xFF64748B),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Tarik s/d Rp 1.000.000 Langsung di Loket • Di atas Rp 1 Juta Wajib Perjanjian H-1',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w800,
                      color: Color(0xFF0F172A),
                      height: 1.3,
                    ),
                  ),
                  const SizedBox(height: 6),
                  const Text(
                    'Penarikan ≤ Rp 1.000.000 tidak butuh izin admin/perjanjian. Sisa saldo tabungan wajib mengendap minimal Rp 10.000.',
                    style: TextStyle(
                      fontSize: 11,
                      color: Color(0xFF475569),
                      height: 1.4,
                    ),
                  ),
                  const SizedBox(height: 12),
                  ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF2563EB),
                      foregroundColor: Colors.white,
                      elevation: 0,
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                    onPressed: () {
                      setState(() {
                        _currentIndex = 1; // Pindah ke Tab Perjanjian
                      });
                    },
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(
                          'Ajukan Perjanjian',
                          style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700),
                        ),
                        SizedBox(width: 6),
                        Icon(Icons.arrow_forward_rounded, size: 14),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),

          // CARD 4: SISTEM ANTREAN LOKET BANK MINI (Border kiri biru 5px, persis seperti di web)
          Card(
            clipBehavior: Clip.antiAlias,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            child: Container(
              decoration: const BoxDecoration(
                color: Colors.white,
                border: Border(left: BorderSide(color: Color(0xFF2563EB), width: 5)),
              ),
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'SISTEM ANTREAN LOKET BANK MINI',
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w700,
                      color: Color(0xFF64748B),
                      letterSpacing: 0.5,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      const Text(
                        'Sedang Dilayani: ',
                        style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: Color(0xFF1E293B)),
                      ),
                      Text(
                        sedangDilayani,
                        style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: Color(0xFF2563EB)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Total antrean yang sedang menunggu hari ini: $totalMenunggu orang',
                    style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),

                  if (nomorSaya != null) ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: const Color(0xFFEFF6FF),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFBFDBFE)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'NOMOR ANTREAN ANDA',
                            style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF2563EB)),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            nomorSaya,
                            style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: Color(0xFF1E293B)),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            statusSaya == 'dilayani'
                                ? 'Silakan menuju loket Teller sekarang'
                                : 'Menunggu (Sisa: $sisaAntrean orang sebelum Anda)',
                            style: TextStyle(
                              fontSize: 11,
                              fontWeight: FontWeight.w700,
                              color: statusSaya == 'dilayani' ? const Color(0xFF16A34A) : const Color(0xFF64748B),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ] else ...[
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF2563EB),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                        ),
                        onPressed: _isTakingAntrean ? null : _handleAmbilAntrean,
                        child: _isTakingAntrean
                            ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                            : const Text('Ambil Nomor Antrean Hari Ini', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700)),
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 14),

          // CARD 5: RIWAYAT MUTASI REKENING (Persis seperti di Web)
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Riwayat Mutasi Rekening',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                  ),
                  const SizedBox(height: 2),
                  const Text(
                    'Daftar seluruh transaksi setoran dan penarikan yang pernah dilakukan.',
                    style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),

                  // FILTER BUTTONS ROW (Semua, Setoran, Penarikan)
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        _buildFilterButton(
                          label: 'Semua (${_mutasiList.length})',
                          isActive: _filterMutasi == 'all',
                          onTap: () => setState(() => _filterMutasi = 'all'),
                        ),
                        const SizedBox(width: 6),
                        _buildFilterButton(
                          label: 'Setoran (${_mutasiList.where((m) => m['jenis_trans'] == 'setoran').length})',
                          isActive: _filterMutasi == 'setoran',
                          onTap: () => setState(() => _filterMutasi = 'setoran'),
                        ),
                        const SizedBox(width: 6),
                        _buildFilterButton(
                          label: 'Penarikan (${_mutasiList.where((m) => m['jenis_trans'] == 'penarikan').length})',
                          isActive: _filterMutasi == 'penarikan',
                          onTap: () => setState(() => _filterMutasi = 'penarikan'),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 10),

                  // SEARCH INPUT
                  TextField(
                    controller: _searchMutasiController,
                    onChanged: (val) => setState(() => _mutasiSearchQuery = val),
                    style: const TextStyle(fontSize: 13),
                    decoration: InputDecoration(
                      hintText: 'Cari No Transaksi, Tanggal...',
                      hintStyle: const TextStyle(fontSize: 12, color: Color(0xFF94A3B8)),
                      prefixIcon: const Icon(Icons.search, size: 18, color: Color(0xFF64748B)),
                      suffixIcon: _mutasiSearchQuery.isNotEmpty
                          ? IconButton(
                              icon: const Icon(Icons.clear, size: 16, color: Color(0xFF94A3B8)),
                              onPressed: () {
                                _searchMutasiController.clear();
                                setState(() => _mutasiSearchQuery = '');
                              },
                            )
                          : null,
                      isDense: true,
                      contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                    ),
                  ),
                  const SizedBox(height: 12),

                  // LIST MUTASI
                  if (_mutasiList.isEmpty)
                    const Padding(
                      padding: EdgeInsets.all(24),
                      child: Center(
                        child: Text(
                          'Belum ada riwayat transaksi mutasi pada rekening ini.',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                        ),
                      ),
                    )
                  else if (_filteredMutasiList.isEmpty)
                    const Padding(
                      padding: EdgeInsets.all(24),
                      child: Center(
                        child: Text(
                          'Tidak ada transaksi yang cocok dengan pencarian.',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                        ),
                      ),
                    )
                  else
                    ListView.separated(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: _filteredMutasiList.length,
                      separatorBuilder: (context, index) => const Divider(height: 1, color: Color(0xFFE2E8F0)),
                      itemBuilder: (context, index) {
                        final item = _filteredMutasiList[index];
                        final isSetoran = item['jenis_trans'] == 'setoran';
                        final nominal = item['nominal'] ?? 0;
                        final noTrans = item['no_transaksi'] ?? '-';
                        final tgl = item['tanggal'] ?? '-';
                        final ket = item['keterangan'] ?? '-';

                        return Padding(
                          padding: const EdgeInsets.symmetric(vertical: 10),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    isSetoran ? 'SETORAN' : 'PENARIKAN',
                                    style: TextStyle(
                                      fontSize: 12,
                                      fontWeight: FontWeight.w800,
                                      color: isSetoran ? const Color(0xFF16A34A) : const Color(0xFFDC2626),
                                    ),
                                  ),
                                  Text(
                                    '${isSetoran ? '+' : '-'} ${_formatRupiah(nominal)}',
                                    style: TextStyle(
                                      fontSize: 13,
                                      fontWeight: FontWeight.w800,
                                      color: isSetoran ? const Color(0xFF16A34A) : const Color(0xFFDC2626),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 3),
                              Text(
                                noTrans,
                                style: const TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.w600,
                                  color: Color(0xFF475569),
                                  fontFamily: 'monospace',
                                ),
                              ),
                              const SizedBox(height: 2),
                              Text(
                                'Waktu: $tgl',
                                style: const TextStyle(fontSize: 11, color: Color(0xFF94A3B8)),
                              ),
                              if (ket.isNotEmpty && ket != '-') ...[
                                const SizedBox(height: 2),
                                Text(
                                  ket,
                                  style: const TextStyle(
                                    fontSize: 11,
                                    color: Color(0xFF64748B),
                                  ),
                                ),
                              ],
                            ],
                          ),
                        );
                      },
                    ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  // -------------------------------------------------------------------------
  // BUILD TAB 1: PERJANJIAN PENARIKAN (> 1 JUTA) (Persis seperti di Web)
  // -------------------------------------------------------------------------
  Widget _buildPerjanjianTab() {
    final rawSaldo = _nasabahData['saldo'] ?? 0;
    final saldo = (rawSaldo is num) ? rawSaldo.toInt() : (int.tryParse(rawSaldo.toString()) ?? 0);
    final maxTarik = saldo > 10000 ? saldo - 10000 : 0;
    final isAktif = _nasabahData['is_aktif'] == true || _nasabahData['status_rekening'] == 'aktif';

    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. CARD FORM PENGAJUAN PERJANJIAN
          Card(
            clipBehavior: Clip.antiAlias,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Form Pengajuan Perjanjian',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Isi formulir ini jika Anda berencana menarik saldo lebih dari Rp 1.000.000 di loket Teller.',
                    style: TextStyle(fontSize: 12, color: Color(0xFF64748B), height: 1.4),
                  ),
                  const SizedBox(height: 14),

                  if (!isAktif)
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: const Color(0xFFFEF2F2),
                        border: Border.all(color: const Color(0xFFFECACA)),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Text(
                        'Rekening Anda belum aktif. Lakukan aktivasi terlebih dahulu di loket Teller untuk dapat mengajukan penarikan.',
                        style: TextStyle(fontSize: 12, color: Color(0xFFB91C1C), height: 1.4),
                      ),
                    )
                  else ...[
                    // INFO BOX SALDO ANDA
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF0FDF4),
                        border: Border.all(color: const Color(0xFFBBF7D0)),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'INFORMASI SALDO ANDA',
                            style: TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: Color(0xFF166534), letterSpacing: 0.5),
                          ),
                          const SizedBox(height: 6),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Text('Saldo Saat Ini', style: TextStyle(fontSize: 11, color: Color(0xFF166534))),
                                  const SizedBox(height: 2),
                                  Text(
                                    _formatRupiah(saldo),
                                    style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: Color(0xFF15803D)),
                                  ),
                                ],
                              ),
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.end,
                                children: [
                                  const Text('Maksimal Bisa Ditarik', style: TextStyle(fontSize: 11, color: Color(0xFF166534))),
                                  const SizedBox(height: 2),
                                  Text(
                                    _formatRupiah(maxTarik),
                                    style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                                  ),
                                ],
                              ),
                            ],
                          ),
                          const SizedBox(height: 6),
                          const Text(
                            '* Sudah memperhitungkan sisa saldo wajib mengendap Rp 10.000.',
                            style: TextStyle(fontSize: 10, color: Color(0xFF166534), fontStyle: FontStyle.italic),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 14),

                    // INPUT 1: NOMINAL PENARIKAN
                    const Text(
                      'Nominal Penarikan (Rp) *',
                      style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                    ),
                    const SizedBox(height: 6),
                    TextField(
                      controller: _pjNominalCtrl,
                      keyboardType: TextInputType.number,
                      decoration: InputDecoration(
                        hintText: 'Contoh: 1500000 (Min. Rp 1.000.001)',
                        prefixText: 'Rp ',
                        prefixStyle: const TextStyle(fontWeight: FontWeight.w700, color: Color(0xFF0F172A)),
                        filled: true,
                        fillColor: const Color(0xFFF8FAFC),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFCBD5E1))),
                        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFCBD5E1))),
                        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFF2563EB), width: 1.5)),
                        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                      ),
                    ),
                    const SizedBox(height: 4),
                    const Text(
                      'Wajib di atas Rp 1.000.000 (minimal Rp 1.000.001).',
                      style: TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                    ),
                    const SizedBox(height: 14),

                    // INPUT 2: TANGGAL RENCANA PENGAMBILAN
                    const Text(
                      'Tanggal Rencana Pengambilan di Loket *',
                      style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                    ),
                    const SizedBox(height: 6),
                    InkWell(
                      onTap: () async {
                        final minDate = DateTime.now().add(const Duration(days: 1));
                        final picked = await showDatePicker(
                          context: context,
                          initialDate: _pjTanggalRencana.isBefore(minDate) ? minDate : _pjTanggalRencana,
                          firstDate: minDate,
                          lastDate: DateTime.now().add(const Duration(days: 180)),
                        );
                        if (picked != null) {
                          setState(() {
                            _pjTanggalRencana = picked;
                          });
                        }
                      },
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                        decoration: BoxDecoration(
                          color: const Color(0xFFF8FAFC),
                          borderRadius: BorderRadius.circular(10),
                          border: Border.all(color: const Color(0xFFCBD5E1)),
                        ),
                        child: Row(
                          children: [
                            const Icon(Icons.calendar_month_rounded, size: 18, color: Color(0xFF2563EB)),
                            const SizedBox(width: 10),
                            Expanded(
                              child: Text(
                                '${_pjTanggalRencana.day.toString().padLeft(2, '0')} ${_namaBulanList[_pjTanggalRencana.month - 1]} ${_pjTanggalRencana.year}',
                                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF0F172A)),
                              ),
                            ),
                            const Text('Ubah', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF2563EB))),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 4),
                    const Text(
                      'Terkunci otomatis minimal besok (H-1) sesuai kebijakan ketersediaan kas fisik.',
                      style: TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                    ),
                    const SizedBox(height: 14),

                    // INPUT 3: KEPERLUAN / ALASAN PENARIKAN
                    const Text(
                      'Keperluan / Alasan Penarikan *',
                      style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                    ),
                    const SizedBox(height: 6),
                    TextField(
                      controller: _pjAlasanCtrl,
                      maxLines: 3,
                      decoration: InputDecoration(
                        hintText: 'Contoh: Biaya sertifikasi kompetensi, pembelian laptop praktik, keperluan sekolah...',
                        hintStyle: const TextStyle(fontSize: 12, color: Color(0xFF94A3B8)),
                        filled: true,
                        fillColor: const Color(0xFFF8FAFC),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFCBD5E1))),
                        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFCBD5E1))),
                        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFF2563EB), width: 1.5)),
                        contentPadding: const EdgeInsets.all(12),
                      ),
                    ),
                    const SizedBox(height: 16),

                    // SUBMIT BUTTON
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF2563EB),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          elevation: 0,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        ),
                        onPressed: _isSubmittingPerjanjian ? null : _handleAjukanPerjanjian,
                        child: _isSubmittingPerjanjian
                            ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                            : const Text('Ajukan Perjanjian ke Teller', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800)),
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 14),

          // 2. CARD DAFTAR PERJANJIAN PENARIKAN
          Card(
            clipBehavior: Clip.antiAlias,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Daftar Perjanjian Penarikan',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                  ),
                  const SizedBox(height: 2),
                  const Text(
                    'Status permohonan penarikan dana di atas 1 juta rupiah.',
                    style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),

                  if (_perjanjianList.isEmpty)
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF8FAFC),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: const Column(
                        children: [
                          Icon(Icons.assignment_outlined, size: 36, color: Color(0xFF94A3B8)),
                          SizedBox(height: 8),
                          Text(
                            'Belum ada pengajuan perjanjian penarikan dana.',
                            style: TextStyle(fontSize: 12, color: Color(0xFF64748B), fontWeight: FontWeight.w600),
                            textAlign: TextAlign.center,
                          ),
                        ],
                      ),
                    )
                  else
                    ListView.separated(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: _perjanjianList.length,
                      separatorBuilder: (_, __) => const SizedBox(height: 10),
                      itemBuilder: (context, index) {
                        final pj = _perjanjianList[index];
                        final nominal = pj['nominal'] is num ? pj['nominal'] : (int.tryParse(pj['nominal'].toString()) ?? 0);
                        final status = (pj['status'] ?? 'menunggu').toString().toLowerCase();
                        final alasan = pj['alasan'] ?? '-';
                        final tglRencana = pj['tanggal_rencana'] ?? '-';
                        final tglDiajukan = pj['created_at'] ?? '-';
                        final catatanTeller = pj['catatan_teller'];

                        Color badgeBg = const Color(0xFFFEF3C7);
                        Color badgeColor = const Color(0xFFB45309);
                        String statusLabel = 'Menunggu Konfirmasi';

                        if (status == 'disetujui' || status == 'approved') {
                          badgeBg = const Color(0xFFDCFCE7);
                          badgeColor = const Color(0xFF15803D);
                          statusLabel = 'Disetujui - Siap Diambil';
                        } else if (status == 'ditolak' || status == 'rejected') {
                          badgeBg = const Color(0xFFFEE2E2);
                          badgeColor = const Color(0xFFDC2626);
                          statusLabel = 'Ditolak';
                        } else if (status == 'selesai' || status == 'completed') {
                          badgeBg = const Color(0xFFEFF6FF);
                          badgeColor = const Color(0xFF2563EB);
                          statusLabel = 'Selesai Ditarik';
                        }

                        return Container(
                          padding: const EdgeInsets.all(14),
                          decoration: BoxDecoration(
                            color: const Color(0xFFF8FAFC),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: const Color(0xFFE2E8F0)),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Row(
                                    children: [
                                      const Icon(Icons.event_outlined, size: 14, color: Color(0xFF64748B)),
                                      const SizedBox(width: 4),
                                      Text(
                                        'Rencana: $tglRencana',
                                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                                      ),
                                    ],
                                  ),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: badgeBg,
                                      borderRadius: BorderRadius.circular(999),
                                    ),
                                    child: Text(
                                      statusLabel,
                                      style: TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: badgeColor),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(
                                _formatRupiah(nominal),
                                style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w900, color: Color(0xFFDC2626)),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                'Keperluan: $alasan',
                                style: const TextStyle(fontSize: 12, color: Color(0xFF334155), height: 1.3),
                              ),
                              const SizedBox(height: 6),
                              Text(
                                'Diajukan: $tglDiajukan',
                                style: const TextStyle(fontSize: 11, color: Color(0xFF94A3B8)),
                              ),
                              if (catatanTeller != null && catatanTeller.toString().trim().isNotEmpty) ...[
                                const SizedBox(height: 8),
                                Container(
                                  width: double.infinity,
                                  padding: const EdgeInsets.all(8),
                                  decoration: BoxDecoration(
                                    color: const Color(0xFFFFFBEB),
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(color: const Color(0xFFFDE68A)),
                                  ),
                                  child: Text(
                                    'Catatan Teller: $catatanTeller',
                                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF92400E)),
                                  ),
                                ),
                              ],
                            ],
                          ),
                        );
                      },
                    ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  // -------------------------------------------------------------------------
  // BUILD TAB 2: LAPORAN BULANAN & TARGET (Persis seperti di Web)
  // -------------------------------------------------------------------------
  Widget _buildLaporanTargetTab() {
    final saldo = _nasabahData['saldo'] ?? 0;
    final laporan = _laporanBulananData ?? _nasabahData['laporan_bulanan'] as Map<String, dynamic>?;

    final saldoAwal = laporan?['saldo_awal'] ?? 0;
    final totalSetoran = laporan?['total_setoran'] ?? 0;
    final totalPenarikan = laporan?['total_penarikan'] ?? 0;
    final saldoAkhir = laporan?['saldo_akhir'] ?? 0;

    final target = _targetTabunganData ?? _nasabahData['target_tabungan'] as Map<String, dynamic>?;

    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. RINGKASAN KEUANGAN BULANAN (Persis seperti di web)
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Ringkasan Keuangan Bulanan',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                  ),
                  const SizedBox(height: 2),
                  const Text(
                    'Rangkuman mutasi keuangan tabungan Anda per periode bulan.',
                    style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),

                  // DROPDOWN BULAN & TAHUN
                  Row(
                    children: [
                      Expanded(
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10),
                          decoration: BoxDecoration(
                            border: Border.all(color: const Color(0xFFCBD5E1)),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: DropdownButtonHideUnderline(
                            child: DropdownButton<int>(
                              value: _selectedBulan,
                              isExpanded: true,
                              items: List.generate(12, (index) {
                                return DropdownMenuItem<int>(
                                  value: index + 1,
                                  child: Text(_namaBulanList[index], style: const TextStyle(fontSize: 13)),
                                );
                              }),
                              onChanged: (val) {
                                if (val != null) {
                                  setState(() => _selectedBulan = val);
                                  _fetchLaporanBulanan();
                                }
                              },
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10),
                        decoration: BoxDecoration(
                          border: Border.all(color: const Color(0xFFCBD5E1)),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: DropdownButtonHideUnderline(
                          child: DropdownButton<int>(
                            value: _selectedTahun,
                            items: [2025, 2026, 2027].map((yr) {
                              return DropdownMenuItem<int>(
                                value: yr,
                                child: Text('$yr', style: const TextStyle(fontSize: 13)),
                              );
                            }).toList(),
                            onChanged: (val) {
                              if (val != null) {
                                setState(() => _selectedTahun = val);
                                _fetchLaporanBulanan();
                              }
                            },
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),

                  if (_isLoadingLaporan)
                    const Center(child: Padding(padding: EdgeInsets.all(20), child: CircularProgressIndicator()))
                  else ...[
                    // 4 BOX RINGKASAN PERSIS SEPERTI DI WEB
                    Row(
                      children: [
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Saldo Awal Bulan',
                            value: _formatRupiah(saldoAwal),
                            color: const Color(0xFF1E293B),
                            bgColor: const Color(0xFFF8FAFC),
                            borderColor: const Color(0xFFE2E8F0),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Total Setoran Masuk (+)',
                            value: '+ ${_formatRupiah(totalSetoran)}',
                            color: const Color(0xFF15803D),
                            bgColor: const Color(0xFFF0FDF4),
                            borderColor: const Color(0xFFBBF7D0),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Total Penarikan Keluar (-)',
                            value: '- ${_formatRupiah(totalPenarikan)}',
                            color: const Color(0xFFB91C1C),
                            bgColor: const Color(0xFFFEF2F2),
                            borderColor: const Color(0xFFFECACA),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Saldo Akhir Bulan',
                            value: _formatRupiah(saldoAkhir),
                            color: const Color(0xFF2563EB),
                            bgColor: const Color(0xFFEFF6FF),
                            borderColor: const Color(0xFFBFDBFE),
                          ),
                        ),
                      ],
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 14),

          // 2. TARGET TABUNGAN SAYA (Persis seperti di Web)
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Target Tabungan Saya',
                              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                            ),
                            SizedBox(height: 2),
                            Text(
                              'Kelola target tabungan untuk memotivasi perencanaan keuangan Anda.',
                              style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                            ),
                          ],
                        ),
                      ),
                      ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF2563EB),
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          textStyle: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700),
                        ),
                        onPressed: () => _showTargetDialog(isEdit: target != null),
                        child: Text(target != null ? 'Perbarui Target' : 'Pasang Target Baru'),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),

                  if (target == null) ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF8FAFC),
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: const Center(
                        child: Text(
                          'Belum ada target tabungan yang dipasang. Pasang target tabungan untuk memantau perkembangan impian Anda.',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 12, color: Color(0xFF64748B), height: 1.4),
                        ),
                      ),
                    ),
                  ] else ...[
                    Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF8FAFC),
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                target['judul_target'] ?? 'Target Tabungan',
                                style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w800, color: Color(0xFF1E293B)),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                decoration: BoxDecoration(
                                  color: (target['persen'] ?? 0) >= 100
                                      ? const Color(0xFFDCFCE7)
                                      : const Color(0xFFDBEAFE),
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: Text(
                                  (target['persen'] ?? 0) >= 100 ? 'Target Tercapai' : 'Sedang Berjalan (${target['persen'] ?? 0}%)',
                                  style: TextStyle(
                                    fontSize: 10,
                                    fontWeight: FontWeight.w700,
                                    color: (target['persen'] ?? 0) >= 100
                                        ? const Color(0xFF15803D)
                                        : const Color(0xFF1D4ED8),
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 8),

                          // PROGRESS BAR ELEGAN
                          ClipRRect(
                            borderRadius: BorderRadius.circular(999),
                            child: LinearProgressIndicator(
                              value: ((target['persen'] ?? 0) / 100).clamp(0.0, 1.0),
                              minHeight: 12,
                              backgroundColor: const Color(0xFFE2E8F0),
                              valueColor: AlwaysStoppedAnimation<Color>(
                                (target['persen'] ?? 0) >= 100
                                    ? const Color(0xFF16A34A)
                                    : const Color(0xFF2563EB),
                              ),
                            ),
                          ),
                          const SizedBox(height: 8),

                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Expanded(
                                child: Text(
                                  'Saldo: ${_formatRupiah(saldo)} / Target: ${_formatRupiah(target['nominal_target'] ?? 0)}',
                                  style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF475569)),
                                ),
                              ),
                              Text(
                                (target['sisa'] ?? 0) > 0
                                    ? 'Sisa: ${_formatRupiah(target['sisa'] ?? 0)}'
                                    : 'Tercapai!',
                                style: TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.w700,
                                  color: (target['sisa'] ?? 0) > 0 ? const Color(0xFFB91C1C) : const Color(0xFF16A34A),
                                ),
                              ),
                            ],
                          ),
                          if (target['keterangan'] != null && (target['keterangan'] as String).isNotEmpty) ...[
                            const SizedBox(height: 8),
                            Text(
                              'Catatan: ${target['keterangan']}',
                              style: const TextStyle(fontSize: 11, fontStyle: FontStyle.italic, color: Color(0xFF64748B)),
                            ),
                          ],
                          const SizedBox(height: 12),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.end,
                            children: [
                              TextButton(
                                onPressed: _handleHapusTarget,
                                style: TextButton.styleFrom(
                                  foregroundColor: const Color(0xFFDC2626),
                                  backgroundColor: const Color(0xFFF1F5F9),
                                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(8),
                                    side: const BorderSide(color: Color(0xFFCBD5E1)),
                                  ),
                                ),
                                child: const Text('Hapus Target', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700)),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  // -------------------------------------------------------------------------
  // BUILD TAB 2: PROFIL & PENGADUAN (Persis seperti di Web)
  // -------------------------------------------------------------------------
  Widget _buildProfilPengaduanTab() {
    final nama = _nasabahData['nama_siswa'] ?? _nasabahData['student_name'] ?? '-';
    final noRek = _nasabahData['no_rekening'] ?? _nasabahData['account_number'] ?? '-';
    final kelas = _nasabahData['kelas'] ?? _nasabahData['class_grade'] ?? '-';
    final nis = _nasabahData['nis'] ?? '-';
    final email = _nasabahData['email'] ?? '-';
    final profil = _profilData ?? _nasabahData['profil'] as Map<String, dynamic>?;
    final thnLulus = profil?['tahun_kelulusan'] ?? '-';

    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. DATA IDENTITAS RESMI SISWA
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const CircleAvatar(
                        radius: 22,
                        backgroundColor: Color(0xFFDBEAFE),
                        child: Icon(Icons.person, color: Color(0xFF2563EB), size: 26),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              nama,
                              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                            ),
                            Text(
                              'NIS: $nis • $kelas',
                              style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 14),
                  const Divider(height: 1, color: Color(0xFFE2E8F0)),
                  const SizedBox(height: 12),
                  _buildProfileRow('Nomor Rekening', noRek),
                  _buildProfileRow('Jurusan & Rombel', kelas),
                  _buildProfileRow('Tahun Kelulusan', thnLulus.toString()),
                  _buildProfileRow('Alamat Gmail', email),
                  _buildProfileRow('Status Akun', 'Rekening Aktif (Terverifikasi)'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 14),

          // 2. GANTI KATA SANDI MANDIRI
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Ganti Kata Sandi Mandiri',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                  ),
                  const SizedBox(height: 2),
                  const Text(
                    'Perbarui kata sandi akun Anda secara berkala demi keamanan.',
                    style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),

                  const Text('Kata Sandi Saat Ini', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700)),
                  const SizedBox(height: 4),
                  TextField(
                    controller: _sandiLamaCtrl,
                    obscureText: _obscureSandiLama,
                    decoration: InputDecoration(
                      hintText: 'Masukkan kata sandi saat ini',
                      suffixIcon: IconButton(
                        icon: Icon(_obscureSandiLama ? Icons.visibility_off : Icons.visibility, size: 18),
                        onPressed: () => setState(() => _obscureSandiLama = !_obscureSandiLama),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),

                  const Text('Kata Sandi Baru (Min. 6 Karakter)', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700)),
                  const SizedBox(height: 4),
                  TextField(
                    controller: _sandiBaruCtrl,
                    obscureText: _obscureSandiBaru,
                    decoration: InputDecoration(
                      hintText: 'Masukkan kata sandi baru',
                      suffixIcon: IconButton(
                        icon: Icon(_obscureSandiBaru ? Icons.visibility_off : Icons.visibility, size: 18),
                        onPressed: () => setState(() => _obscureSandiBaru = !_obscureSandiBaru),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),

                  const Text('Ulangi Kata Sandi Baru', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700)),
                  const SizedBox(height: 4),
                  TextField(
                    controller: _sandiKonfCtrl,
                    obscureText: _obscureSandiKonf,
                    decoration: InputDecoration(
                      hintText: 'Konfirmasi kata sandi baru',
                      suffixIcon: IconButton(
                        icon: Icon(_obscureSandiKonf ? Icons.visibility_off : Icons.visibility, size: 18),
                        onPressed: () => setState(() => _obscureSandiKonf = !_obscureSandiKonf),
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),

                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _isUpdatingPassword ? null : _handleGantiPassword,
                      child: _isUpdatingPassword
                          ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                          : const Text('Perbarui Kata Sandi'),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 14),

          // 3. LAYANAN PENGADUAN NASABAH
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Pengaduan Nasabah',
                              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                            ),
                            SizedBox(height: 2),
                            Text(
                              'Sampaikan keluhan atau pertanyaan Anda.',
                              style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                            ),
                          ],
                        ),
                      ),
                      ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          textStyle: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700),
                        ),
                        onPressed: _showPengaduanDialog,
                        child: const Text('Buat Aduan'),
                      ),
                    ],
                  ),
                  const SizedBox(height: 14),

                  if (_pengaduanList.isEmpty) ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF8FAFC),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: const Center(
                        child: Text(
                          'Belum ada riwayat pengaduan nasabah yang diajukan.',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                        ),
                      ),
                    ),
                  ] else ...[
                    ListView.separated(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: _pengaduanList.length,
                      separatorBuilder: (context, index) => const SizedBox(height: 10),
                      itemBuilder: (context, index) {
                        final p = _pengaduanList[index];
                        final status = (p['status'] ?? 'menunggu').toString().toLowerCase();

                        return Container(
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: const Color(0xFFF8FAFC),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: const Color(0xFFE2E8F0)),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    p['kategori'] ?? 'Pengaduan',
                                    style: const TextStyle(
                                      fontSize: 11,
                                      fontWeight: FontWeight.w700,
                                      color: Color(0xFF2563EB),
                                    ),
                                  ),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(
                                      color: status == 'selesai'
                                          ? const Color(0xFFDCFCE7)
                                          : status == 'proses'
                                              ? const Color(0xFFDBEAFE)
                                              : const Color(0xFFFEF3C7),
                                      borderRadius: BorderRadius.circular(10),
                                    ),
                                    child: Text(
                                      status == 'selesai'
                                          ? 'Selesai'
                                          : status == 'proses'
                                              ? 'Ditanggapi'
                                              : 'Menunggu',
                                      style: TextStyle(
                                        fontSize: 10,
                                        fontWeight: FontWeight.w700,
                                        color: status == 'selesai'
                                            ? const Color(0xFF15803D)
                                            : status == 'proses'
                                                ? const Color(0xFF1D4ED8)
                                                : const Color(0xFFB45309),
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 4),
                              Text(
                                p['judul'] ?? '-',
                                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                              ),
                              const SizedBox(height: 2),
                              Text(
                                p['isi_pesan'] ?? '-',
                                style: const TextStyle(fontSize: 12, color: Color(0xFF475569)),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                'Dikirim: ${p['created_at'] ?? '-'}',
                                style: const TextStyle(fontSize: 10, color: Color(0xFF94A3B8)),
                              ),

                              if (p['tanggapan_admin'] != null && (p['tanggapan_admin'] as String).isNotEmpty) ...[
                                const SizedBox(height: 8),
                                Container(
                                  padding: const EdgeInsets.all(8),
                                  decoration: BoxDecoration(
                                    color: const Color(0xFFEFF6FF),
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(color: const Color(0xFFBFDBFE)),
                                  ),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      const Row(
                                        children: [
                                          Icon(Icons.admin_panel_settings, size: 14, color: Color(0xFF2563EB)),
                                          SizedBox(width: 4),
                                          Text(
                                            'Tanggapan Administrator Bank Mini:',
                                            style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF1E40AF)),
                                          ),
                                        ],
                                      ),
                                      const SizedBox(height: 2),
                                      Text(
                                        p['tanggapan_admin'],
                                        style: const TextStyle(fontSize: 11, color: Color(0xFF1E293B)),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ],
                          ),
                        );
                      },
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  // -------------------------------------------------------------------------
  // REUSABLE HELPER WIDGETS
  // -------------------------------------------------------------------------
  Widget _buildFilterButton({required String label, required bool isActive, required VoidCallback onTap}) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(8),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
        decoration: BoxDecoration(
          color: isActive ? const Color(0xFF0F172A) : Colors.white,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: isActive ? const Color(0xFF0F172A) : const Color(0xFFE2E8F0)),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.w700,
            color: isActive ? Colors.white : const Color(0xFF334155),
          ),
        ),
      ),
    );
  }

  Widget _buildProfileRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(label, style: const TextStyle(fontSize: 12, color: Color(0xFF64748B))),
          ),
          const Text(': ', style: TextStyle(fontSize: 12, color: Color(0xFF64748B))),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF0F172A)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMetricCard({
    required String label,
    required String value,
    required Color color,
    required Color bgColor,
    required Color borderColor,
  }) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: borderColor),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 11, color: Color(0xFF64748B), fontWeight: FontWeight.w700)),
          const SizedBox(height: 4),
          Text(
            value,
            style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: color),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final nama = _nasabahData['nama_siswa'] ?? _nasabahData['student_name'] ?? 'Siswa';
    final statusLayanan = (_nasabahData['status_layanan'] ?? 'buka').toString().toLowerCase();

    return Scaffold(
      // NAVBAR PUTIH BERSIH PERSIS SEPERTI DI WEB DENGAN LOGO BANK MINI
      appBar: AppBar(
        titleSpacing: 12,
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 38,
              height: 38,
              padding: const EdgeInsets.all(2),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: const Color(0xFFE2E8F0)),
                boxShadow: const [
                  BoxShadow(
                    color: Color(0x0A0F172A),
                    blurRadius: 4,
                    offset: Offset(0, 2),
                  ),
                ],
              ),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(6),
                child: Image.asset(
                  'assets/images/logo_bank_mini.png',
                  fit: BoxFit.contain,
                  errorBuilder: (_, __, ___) => Image.network(
                    'https://skansabaewallet.web.id/images/logo_bank_mini.png',
                    fit: BoxFit.contain,
                  ),
                ),
              ),
            ),
            const SizedBox(width: 8),
            const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  'Nasabah',
                  style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w800,
                    color: Color(0xFF0F172A),
                  ),
                ),
                Text(
                  'E-Teller Bank Mini',
                  style: TextStyle(
                    fontSize: 10,
                    color: Color(0xFF64748B),
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ],
        ),
        actions: [
          Center(
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Text(
                  'Teller = ',
                  style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF475569)),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(
                    color: statusLayanan == 'buka'
                        ? const Color(0xFFDCFCE7)
                        : statusLayanan == 'istirahat'
                            ? const Color(0xFFFEF3C7)
                            : const Color(0xFFFEE2E2),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(
                      color: statusLayanan == 'buka'
                          ? const Color(0xFFBBF7D0)
                          : statusLayanan == 'istirahat'
                              ? const Color(0xFFFDE68A)
                              : const Color(0xFFFECACA),
                    ),
                  ),
                  child: Text(
                    statusLayanan == 'buka' ? 'Buka' : statusLayanan == 'istirahat' ? 'Istirahat' : 'Tutup',
                    style: TextStyle(
                      fontSize: 10,
                      fontWeight: FontWeight.w800,
                      color: statusLayanan == 'buka'
                          ? const Color(0xFF15803D)
                          : statusLayanan == 'istirahat'
                              ? const Color(0xFFB45309)
                              : const Color(0xFFB91C1C),
                    ),
                  ),
                ),
                const SizedBox(width: 6),
                Text(
                  'Halo, $nama',
                  style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF334155)),
                ),
                const SizedBox(width: 8),
                InkWell(
                  onTap: _handleLogout,
                  borderRadius: BorderRadius.circular(8),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                    decoration: BoxDecoration(
                      color: const Color(0xFFEF4444),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Text(
                      'Keluar',
                      style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
              ],
            ),
          ),
        ],
        bottom: const PreferredSize(
          preferredSize: Size.fromHeight(1),
          child: Divider(height: 1, color: Color(0xFFE2E8F0)),
        ),
      ),
      body: RefreshIndicator(
        onRefresh: _fetchDashboardData,
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : IndexedStack(
                index: _currentIndex,
                children: [
                  _buildDashboardTab(),
                  _buildPerjanjianTab(),
                  _buildLaporanTargetTab(),
                  _buildProfilPengaduanTab(),
                ],
              ),
      ),
      // BOTTOM NAVIGATION BAR TETAP PERMANEN DI BAWAH PADA MOBILE
      bottomNavigationBar: Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          border: Border(top: BorderSide(color: Color(0xFFE2E8F0))),
          boxShadow: [
            BoxShadow(
              color: Color(0x0D0F172A),
              blurRadius: 10,
              offset: Offset(0, -3),
            ),
          ],
        ),
        child: BottomNavigationBar(
          currentIndex: _currentIndex,
          onTap: (index) {
            setState(() {
              _currentIndex = index;
            });
          },
          items: const [
            BottomNavigationBarItem(
              icon: Icon(Icons.dashboard_outlined),
              activeIcon: Icon(Icons.dashboard_rounded),
              label: 'Dashboard',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.handshake_outlined),
              activeIcon: Icon(Icons.handshake_rounded),
              label: 'Perjanjian',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.bar_chart_outlined),
              activeIcon: Icon(Icons.bar_chart_rounded),
              label: 'Laporan & Target',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.person_outline_rounded),
              activeIcon: Icon(Icons.person_rounded),
              label: 'Profil & Pengaduan',
            ),
          ],
        ),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// 3. DIALOG PEMULIHAN SANDI AKUN VIA OTP GMAIL
// ---------------------------------------------------------------------------
class _LupaSandiOtpDialog extends StatefulWidget {
  const _LupaSandiOtpDialog();

  @override
  State<_LupaSandiOtpDialog> createState() => _LupaSandiOtpDialogState();
}

class _LupaSandiOtpDialogState extends State<_LupaSandiOtpDialog> {
  int _step = 1;
  final TextEditingController _identifierController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _otpController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _confirmPasswordController = TextEditingController();

  bool _isSendingOtp = false;
  bool _isVerifyingOtp = false;
  bool _isSavingPassword = false;
  bool _showPassword = false;
  bool _showConfirmPassword = false;

  String? _alertMsg;
  bool _isAlertSuccess = false;

  String? _resetToken;
  String? _verifiedNama;
  String? _verifiedUsername;

  @override
  void dispose() {
    _identifierController.dispose();
    _emailController.dispose();
    _otpController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  Future<void> _handleKirimOtp() async {
    final identifier = _identifierController.text.trim();
    final email = _emailController.text.trim();

    if (identifier.isEmpty) {
      setState(() {
        _alertMsg = 'Silakan masukkan NISN atau Username akun Anda.';
        _isAlertSuccess = false;
      });
      return;
    }
    if (email.isEmpty || !email.contains('@')) {
      setState(() {
        _alertMsg = 'Silakan masukkan alamat Gmail terdaftar yang valid.';
        _isAlertSuccess = false;
      });
      return;
    }

    setState(() {
      _isSendingOtp = true;
      _alertMsg = null;
    });

    try {
      final res = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/lupa-sandi/kirim-otp'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'identifier': identifier,
          'email': email,
        }),
      );

      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['success'] == true) {
        setState(() {
          _isAlertSuccess = true;
          _alertMsg = data['message'] ?? 'Kode OTP telah berhasil dikirim ke alamat Gmail Anda.';
          if (data['otp_dev'] != null) {
            _otpController.text = data['otp_dev'].toString();
          }
        });
      } else {
        setState(() {
          _isAlertSuccess = false;
          _alertMsg = data['message'] ?? 'Gagal mengirim kode OTP. Periksa data Anda.';
        });
      }
    } catch (e) {
      setState(() {
        _isAlertSuccess = false;
        _alertMsg = 'Terjadi kesalahan koneksi server: $e';
      });
    } finally {
      if (mounted) {
        setState(() => _isSendingOtp = false);
      }
    }
  }

  Future<void> _handleVerifikasiOtp() async {
    final identifier = _identifierController.text.trim();
    final email = _emailController.text.trim();
    final otp = _otpController.text.trim();

    if (identifier.isEmpty || email.isEmpty) {
      setState(() {
        _alertMsg = 'Harap isi NISN/Username dan Gmail terlebih dahulu.';
        _isAlertSuccess = false;
      });
      return;
    }

    if (otp.length != 6) {
      setState(() {
        _alertMsg = 'Masukkan 6 digit kode OTP yang telah dikirimkan.';
        _isAlertSuccess = false;
      });
      return;
    }

    setState(() {
      _isVerifyingOtp = true;
      _alertMsg = null;
    });

    try {
      final res = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/lupa-sandi/verifikasi-otp'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'identifier': identifier,
          'email': email,
          'otp': otp,
        }),
      );

      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['success'] == true) {
        setState(() {
          _resetToken = data['reset_token'];
          _verifiedNama = data['nama'];
          _verifiedUsername = data['username'];
          _step = 2;
          _alertMsg = null;
        });
      } else {
        setState(() {
          _isAlertSuccess = false;
          _alertMsg = data['message'] ?? 'Kode OTP salah atau telah kadaluarsa.';
        });
      }
    } catch (e) {
      setState(() {
        _isAlertSuccess = false;
        _alertMsg = 'Terjadi kesalahan verifikasi: $e';
      });
    } finally {
      if (mounted) {
        setState(() => _isVerifyingOtp = false);
      }
    }
  }

  Future<void> _handleSimpanSandi() async {
    final password = _passwordController.text;
    final confirmPassword = _confirmPasswordController.text;

    if (password.length < 6) {
      setState(() {
        _alertMsg = 'Kata sandi baru minimal terdiri dari 6 karakter.';
        _isAlertSuccess = false;
      });
      return;
    }

    if (password != confirmPassword) {
      setState(() {
        _alertMsg = 'Konfirmasi sandi tidak sesuai dengan sandi baru.';
        _isAlertSuccess = false;
      });
      return;
    }

    if (_resetToken == null) {
      setState(() {
        _alertMsg = 'Sesi verifikasi telah berakhir, silakan ulangi proses.';
        _isAlertSuccess = false;
      });
      return;
    }

    setState(() {
      _isSavingPassword = true;
      _alertMsg = null;
    });

    try {
      final res = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/lupa-sandi/simpan-sandi'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'reset_token': _resetToken,
          'password': password,
          'password_confirmation': confirmPassword,
        }),
      );

      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['success'] == true) {
        if (mounted) {
          Navigator.pop(context);
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              backgroundColor: const Color(0xFF16A34A),
              content: Text(
                data['message'] ?? 'Kata sandi berhasil diperbarui! Silakan masuk dengan sandi baru.',
                style: const TextStyle(fontWeight: FontWeight.w700),
              ),
            ),
          );
        }
      } else {
        setState(() {
          _isAlertSuccess = false;
          _alertMsg = data['message'] ?? 'Gagal menyimpan kata sandi baru.';
        });
      }
    } catch (e) {
      setState(() {
        _isAlertSuccess = false;
        _alertMsg = 'Terjadi kesalahan: $e';
      });
    } finally {
      if (mounted) {
        setState(() => _isSavingPassword = false);
      }
    }
  }

  InputDecoration _inputDeco({required String hint, Widget? suffix}) {
    return InputDecoration(
      hintText: hint,
      hintStyle: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13),
      suffixIcon: suffix,
      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      filled: true,
      fillColor: Colors.white,
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: Color(0xFFCBD5E1)),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: Color(0xFFCBD5E1)),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: Color(0xFF2563EB), width: 1.5),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      backgroundColor: Colors.white,
      insetPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
      child: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(22),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Pemulihan Sandi Akun',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.w800,
                      color: Color(0xFF0F172A),
                    ),
                  ),
                  InkWell(
                    onTap: () => Navigator.pop(context),
                    borderRadius: BorderRadius.circular(8),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF1F5F9),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Text(
                        'Tutup',
                        style: TextStyle(
                          color: Color(0xFF475569),
                          fontWeight: FontWeight.w700,
                          fontSize: 13,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),

              Text(
                _step == 1
                    ? 'Masukkan NISN atau Username akun Anda dan alamat Gmail. Sistem akan mengirimkan kode OTP untuk proses verifikasi kata sandi baru.'
                    : 'Kode OTP terverifikasi. Berikut nama pemilik akun Anda. Silakan tentukan kata sandi baru untuk akun ini di bawah.',
                style: const TextStyle(
                  fontSize: 13,
                  color: Color(0xFF64748B),
                  height: 1.45,
                ),
              ),
              const SizedBox(height: 14),

              if (_alertMsg != null) ...[
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  decoration: BoxDecoration(
                    color: _isAlertSuccess ? const Color(0xFFDCFCE7) : const Color(0xFFFEF2F2),
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(
                      color: _isAlertSuccess ? const Color(0xFF86EFAC) : const Color(0xFFFECACA),
                    ),
                  ),
                  child: Text(
                    _alertMsg!,
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: _isAlertSuccess ? const Color(0xFF166534) : const Color(0xFF991B1B),
                    ),
                  ),
                ),
                const SizedBox(height: 14),
              ],

              if (_step == 1) ...[
                const Text(
                  'NISN atau Username',
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _identifierController,
                  decoration: _inputDeco(hint: 'Contoh: 1029384756 atau username'),
                ),
                const SizedBox(height: 14),

                const Text(
                  'Alamat Gmail Terdaftar',
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: _inputDeco(hint: 'Contoh: ahmad.rizky@gmail.com'),
                ),
                const SizedBox(height: 16),

                SizedBox(
                  width: double.infinity,
                  height: 46,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF2563EB),
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                    onPressed: _isSendingOtp ? null : _handleKirimOtp,
                    child: _isSendingOtp
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                          )
                        : const Text(
                            'Kirim Kode OTP',
                            style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800),
                          ),
                  ),
                ),
                const SizedBox(height: 18),

                const Text(
                  'Masukkan Kode OTP',
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _otpController,
                  keyboardType: TextInputType.number,
                  textAlign: TextAlign.center,
                  maxLength: 6,
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 4.0,
                    color: Color(0xFF0F172A),
                  ),
                  decoration: _inputDeco(
                    hint: '6   D i g i t   O T P',
                  ).copyWith(counterText: ''),
                ),
                const SizedBox(height: 16),

                SizedBox(
                  width: double.infinity,
                  height: 46,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF059669),
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                    onPressed: _isVerifyingOtp ? null : _handleVerifikasiOtp,
                    child: _isVerifyingOtp
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                          )
                        : const Text(
                            'Selanjutnya',
                            style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800),
                          ),
                  ),
                ),
              ] else ...[
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: const Color(0xFFEFF6FF),
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(color: const Color(0xFFBFDBFE)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Nama Pemilik Akun',
                        style: TextStyle(fontSize: 11, color: Color(0xFF64748B), fontWeight: FontWeight.w600),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        _verifiedNama ?? '-',
                        style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        'Username / NISN: ${_verifiedUsername ?? '-'}',
                        style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 16),

                const Text(
                  'Sandi Baru',
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _passwordController,
                  obscureText: !_showPassword,
                  decoration: _inputDeco(
                    hint: 'Masukkan kata sandi baru (min. 6 karakter)',
                    suffix: IconButton(
                      icon: Icon(
                        _showPassword ? Icons.visibility_off : Icons.visibility,
                        color: const Color(0xFF64748B),
                        size: 20,
                      ),
                      onPressed: () => setState(() => _showPassword = !_showPassword),
                    ),
                  ),
                ),
                const SizedBox(height: 14),

                const Text(
                  'Konfirmasi Sandi Baru',
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF1E293B)),
                ),
                const SizedBox(height: 6),
                TextField(
                  controller: _confirmPasswordController,
                  obscureText: !_showConfirmPassword,
                  decoration: _inputDeco(
                    hint: 'Ulangi kata sandi baru',
                    suffix: IconButton(
                      icon: Icon(
                        _showConfirmPassword ? Icons.visibility_off : Icons.visibility,
                        color: const Color(0xFF64748B),
                        size: 20,
                      ),
                      onPressed: () => setState(() => _showConfirmPassword = !_showConfirmPassword),
                    ),
                  ),
                ),
                const SizedBox(height: 18),

                SizedBox(
                  width: double.infinity,
                  height: 46,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF2563EB),
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                    ),
                    onPressed: _isSavingPassword ? null : _handleSimpanSandi,
                    child: _isSavingPassword
                        ? const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                          )
                        : const Text(
                            'Simpan Sandi Baru',
                            style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800),
                          ),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }
}
