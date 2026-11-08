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
      title: 'E-Teller Bank Mini Sekolah',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF2563EB),
          primary: const Color(0xFF2563EB),
          surface: const Color(0xFFF8FAFC),
        ),
        scaffoldBackgroundColor: const Color(0xFFF1F5F9),
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFF2563EB),
          foregroundColor: Colors.white,
          elevation: 0,
          centerTitle: false,
          titleTextStyle: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: Colors.white,
          ),
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
          contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Color(0xFFCBD5E1)),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Color(0xFFCBD5E1)),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Color(0xFF2563EB), width: 2),
          ),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            backgroundColor: const Color(0xFF2563EB),
            foregroundColor: Colors.white,
            padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 20),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            elevation: 1,
            textStyle: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w700,
            ),
          ),
        ),
        bottomNavigationBarTheme: const BottomNavigationBarThemeData(
          backgroundColor: Colors.white,
          selectedItemColor: Color(0xFF2563EB),
          unselectedItemColor: Color(0xFF64748B),
          selectedLabelStyle: TextStyle(fontSize: 12, fontWeight: FontWeight.w700),
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
  // Default URL Server Live Production (DomaiNesia HTTPS)
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
                // Header Brand
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: const Color(0xFF2563EB),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Column(
                    children: [
                      Text(
                        'E-TELLER BANK MINI SEKOLAH',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w800,
                          color: Colors.white,
                          letterSpacing: 0.5,
                        ),
                      ),
                      SizedBox(height: 4),
                      Text(
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
                                const Icon(Icons.info_outline,
                                    size: 18, color: Color(0xFFDC2626)),
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
                                _obscurePassword
                                    ? Icons.visibility_off
                                    : Icons.visibility,
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
                                    child: CircularProgressIndicator(
                                      color: Colors.white,
                                      strokeWidth: 2,
                                    ),
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
  int _currentIndex = 0; // 0: Dashboard, 1: Laporan & Target, 2: Profil & Pengaduan
  late Map<String, dynamic> _nasabahData;
  List<dynamic> _mutasiList = [];
  bool _isLoading = true;
  bool _isRefreshing = false;
  Timer? _statusPollingTimer;

  // Filter & Pencarian Mutasi (Tab 0)
  String _filterMutasi = 'all'; // 'all', 'setoran', 'penarikan'
  final TextEditingController _searchMutasiController = TextEditingController();
  String _mutasiSearchQuery = '';

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
    super.dispose();
  }

  Future<void> _fetchStatusSilent() async {
    try {
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
          });
        }
      }
    } catch (_) {}
  }

  Future<void> _fetchDashboardData() async {
    try {
      // 1. Fetch Dashboard Utama (Profil, Saldo, Antrean, Target, Laporan, Pengaduan)
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
          });
        }
      }

      // 2. Fetch Mutasi Transaksi
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

  // -------------------------------------------------------------------------
  // ACTION: AMBIL ANTREAN LOKET
  // -------------------------------------------------------------------------
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

  // -------------------------------------------------------------------------
  // ACTION: SIMPAN / HAPUS TARGET TABUNGAN
  // -------------------------------------------------------------------------
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
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
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

  // -------------------------------------------------------------------------
  // ACTION: PENGADUAN NASABAH
  // -------------------------------------------------------------------------
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
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
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
                        borderRadius: BorderRadius.circular(12),
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

  // -------------------------------------------------------------------------
  // ACTION: GANTI PASSWORD
  // -------------------------------------------------------------------------
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

  // -------------------------------------------------------------------------
  // HELPER FORMAT RUPIAH
  // -------------------------------------------------------------------------
  String _formatRupiah(num number) {
    final str = number.toStringAsFixed(0);
    final reg = RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))');
    final result = str.replaceAllMapped(reg, (Match m) => '${m[1]}.');
    return 'Rp $result';
  }

  // -------------------------------------------------------------------------
  // MODAL QR CODE
  // -------------------------------------------------------------------------
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
  // BUILD TAB 0: DASHBOARD
  // -------------------------------------------------------------------------
  Widget _buildDashboardTab() {
    final nama = _nasabahData['nama_siswa'] ?? _nasabahData['student_name'] ?? 'Siswa';
    final noRek = _nasabahData['no_rekening'] ?? _nasabahData['account_number'] ?? '-';
    final kelas = _nasabahData['kelas'] ?? _nasabahData['class_grade'] ?? '-';
    final nis = _nasabahData['nis'] ?? '-';
    final saldo = _nasabahData['saldo'] ?? 0;
    final antrean = _nasabahData['antrean'] as Map<String, dynamic>?;

    final sedangDilayani = antrean?['sedang_dilayani'] ?? '-';
    final totalMenunggu = antrean?['total_menunggu'] ?? 0;
    final nomorSaya = antrean?['nomor_saya'];
    final statusSaya = antrean?['status_saya'];
    final sisaAntrean = antrean?['sisa_antrean'] ?? 0;

    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. CARD IDENTITAS & REKENING
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
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
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: const Color(0xFFDCFCE7),
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: const Text(
                          'AKTIF',
                          style: TextStyle(
                            fontSize: 10,
                            fontWeight: FontWeight.w800,
                            color: Color(0xFF15803D),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),
                  Text(
                    noRek,
                    style: const TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.w800,
                      color: Color(0xFF2563EB),
                      letterSpacing: 1,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Divider(height: 1, color: Color(0xFFE2E8F0)),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Identitas Siswa',
                            style: TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                          ),
                          Text(
                            nama,
                            style: const TextStyle(
                              fontSize: 13,
                              fontWeight: FontWeight.w700,
                              color: Color(0xFF0F172A),
                            ),
                          ),
                          Text(
                            '$kelas (NIS: $nis)',
                            style: const TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                          ),
                        ],
                      ),
                      OutlinedButton(
                        onPressed: _showQrModal,
                        style: OutlinedButton.styleFrom(
                          side: const BorderSide(color: Color(0xFFBFDBFE)),
                          backgroundColor: const Color(0xFFEFF6FF),
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                        ),
                        child: const Text(
                          'Lihat QR',
                          style: TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF2563EB),
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),

          // 2. CARD SALDO REAL-TIME
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
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Color(0xFF16A34A),
                    ),
                  ),
                  const SizedBox(height: 4),
                  const Row(
                    children: [
                      Icon(Icons.check_circle, size: 14, color: Color(0xFF16A34A)),
                      SizedBox(width: 4),
                      Text(
                        'Status: Rekening Aktif',
                        style: TextStyle(fontSize: 11, color: Color(0xFF16A34A), fontWeight: FontWeight.w600),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),

          // 3. CARD SISTEM ANTREAN LOKET BANK MINI
          Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFBFDBFE), width: 1.5),
            ),
            child: Container(
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(16),
                gradient: const LinearGradient(
                  colors: [Color(0xFFF8FAFC), Color(0xFFEFF6FF)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'SISTEM ANTREAN LOKET BANK MINI',
                        style: TextStyle(
                          fontSize: 11,
                          fontWeight: FontWeight.w800,
                          color: Color(0xFF1E40AF),
                          letterSpacing: 0.5,
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(
                          color: const Color(0xFFDBEAFE),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Text(
                          'Loket Aktif',
                          style: TextStyle(
                            fontSize: 10,
                            fontWeight: FontWeight.w700,
                            color: Color(0xFF1E40AF),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      const Text(
                        'Sedang Dilayani: ',
                        style: TextStyle(fontSize: 13, color: Color(0xFF475569), fontWeight: FontWeight.w600),
                      ),
                      Text(
                        sedangDilayani,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w800,
                          color: Color(0xFF2563EB),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 2),
                  Text(
                    'Total antrean yang sedang menunggu hari ini: $totalMenunggu orang',
                    style: const TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 12),

                  // KONDISI: SUDAH AMBIL ANTREAN ATAU BELUM
                  if (nomorSaya != null) ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: statusSaya == 'dilayani'
                            ? const Color(0xFFDCFCE7)
                            : const Color(0xFFEFF6FF),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: statusSaya == 'dilayani'
                              ? const Color(0xFF86EFAC)
                              : const Color(0xFFBFDBFE),
                        ),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              const Text(
                                'Nomor Antrean Anda',
                                style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF475569)),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                decoration: BoxDecoration(
                                  color: statusSaya == 'dilayani'
                                      ? const Color(0xFF16A34A)
                                      : const Color(0xFF2563EB),
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Text(
                                  statusSaya == 'dilayani'
                                      ? 'Sedang Dilayani'
                                      : 'Menunggu Giliran',
                                  style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Colors.white),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 4),
                          Text(
                            nomorSaya,
                            style: TextStyle(
                              fontSize: 22,
                              fontWeight: FontWeight.w900,
                              color: statusSaya == 'dilayani'
                                  ? const Color(0xFF15803D)
                                  : const Color(0xFF1E40AF),
                            ),
                          ),
                          if (statusSaya == 'menunggu')
                            Text(
                              sisaAntrean > 0
                                  ? 'Sisa antrean di depan Anda: $sisaAntrean orang'
                                  : 'Giliran Anda berikutnya! Silakan bersiap di loket.',
                              style: TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.w600,
                                color: sisaAntrean > 0 ? const Color(0xFF475569) : const Color(0xFF16A34A),
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
                        ),
                        onPressed: _isTakingAntrean ? null : _handleAmbilAntrean,
                        child: _isTakingAntrean
                            ? const SizedBox(
                                width: 18,
                                height: 18,
                                child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                              )
                            : const Text('Ambil Nomor Antrean Hari Ini'),
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 14),

          // 4. TOMBOL TAMPILKAN KODE QR KARTU DIGITAL
          ElevatedButton(
            onPressed: _showQrModal,
            style: ElevatedButton.styleFrom(padding: const EdgeInsets.symmetric(vertical: 14)),
            child: const Text('Tampilkan Kode QR Kartu Digital Nasabah', textAlign: TextAlign.center),
          ),
          const SizedBox(height: 20),

          // 5. RIWAYAT MUTASI REKENING (Sesuai instruksi: berada di Dashboard)
          const Text(
            'Riwayat Mutasi Rekening',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
          ),
          const SizedBox(height: 2),
          const Text(
            'Daftar seluruh transaksi setoran dan penarikan yang pernah dilakukan.',
            style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
          ),
          const SizedBox(height: 12),

          // FILTER & SEARCH BAR MUTASI
          if (_mutasiList.isNotEmpty) ...[
            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: [
                  _buildFilterChip(
                    label: 'Semua (${_mutasiList.length})',
                    isSelected: _filterMutasi == 'all',
                    selectedColor: const Color(0xFF0F172A),
                    onSelected: () => setState(() => _filterMutasi = 'all'),
                  ),
                  const SizedBox(width: 8),
                  _buildFilterChip(
                    label: 'Setoran (${_mutasiList.where((m) => m['jenis_trans'] == 'setoran').length})',
                    isSelected: _filterMutasi == 'setoran',
                    selectedColor: const Color(0xFF16A34A),
                    onSelected: () => setState(() => _filterMutasi = 'setoran'),
                  ),
                  const SizedBox(width: 8),
                  _buildFilterChip(
                    label: 'Penarikan (${_mutasiList.where((m) => m['jenis_trans'] == 'penarikan').length})',
                    isSelected: _filterMutasi == 'penarikan',
                    selectedColor: const Color(0xFFDC2626),
                    onSelected: () => setState(() => _filterMutasi = 'penarikan'),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 10),

            TextField(
              controller: _searchMutasiController,
              onChanged: (val) => setState(() => _mutasiSearchQuery = val),
              style: const TextStyle(fontSize: 13),
              decoration: InputDecoration(
                hintText: 'Cari No Transaksi, Tanggal, Nominal...',
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
            const SizedBox(height: 10),
          ],

          if (_mutasiList.isEmpty)
            const Card(
              child: Padding(
                padding: EdgeInsets.all(28),
                child: Center(
                  child: Column(
                    children: [
                      Icon(Icons.receipt_long_outlined, size: 40, color: Color(0xFF94A3B8)),
                      SizedBox(height: 8),
                      Text(
                        'Belum ada riwayat transaksi mutasi pada rekening ini.',
                        textAlign: TextAlign.center,
                        style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                      ),
                    ],
                  ),
                ),
              ),
            )
          else if (_filteredMutasiList.isEmpty)
            const Card(
              child: Padding(
                padding: EdgeInsets.all(24),
                child: Center(
                  child: Column(
                    children: [
                      Icon(Icons.filter_alt_off_outlined, size: 36, color: Color(0xFF94A3B8)),
                      SizedBox(height: 8),
                      Text(
                        'Tidak ada transaksi yang cocok dengan pencarian.',
                        textAlign: TextAlign.center,
                        style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                      ),
                    ],
                  ),
                ),
              ),
            )
          else
            ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: _filteredMutasiList.length,
              separatorBuilder: (context, index) => const SizedBox(height: 8),
              itemBuilder: (context, index) {
                final item = _filteredMutasiList[index];
                final isSetoran = item['jenis_trans'] == 'setoran';
                final nominal = item['nominal'] ?? 0;
                final noTrans = item['no_transaksi'] ?? '-';
                final tgl = item['tanggal'] ?? '-';
                final ket = item['keterangan'] ?? '-';

                return Card(
                  child: Padding(
                    padding: const EdgeInsets.all(14),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: isSetoran ? const Color(0xFFDCFCE7) : const Color(0xFFFEE2E2),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Icon(
                            isSetoran ? Icons.arrow_downward : Icons.arrow_upward,
                            color: isSetoran ? const Color(0xFF16A34A) : const Color(0xFFDC2626),
                            size: 20,
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
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
                              const SizedBox(height: 4),
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
                                  'Ket: $ket',
                                  style: const TextStyle(
                                    fontSize: 11,
                                    fontStyle: FontStyle.italic,
                                    color: Color(0xFF64748B),
                                  ),
                                ),
                              ],
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  // -------------------------------------------------------------------------
  // BUILD TAB 1: LAPORAN BULANAN & TARGET
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
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. RINGKASAN KEUANGAN BULANAN
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
                    'Rekapitulasi tabungan siswa per bulan',
                    style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),

                  // FILTER BULAN & TAHUN
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
                    // 4 BOX RINGKASAN
                    Row(
                      children: [
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Saldo Awal',
                            value: _formatRupiah(saldoAwal),
                            color: const Color(0xFF475569),
                            bgColor: const Color(0xFFF1F5F9),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Total Setoran',
                            value: '+ ${_formatRupiah(totalSetoran)}',
                            color: const Color(0xFF16A34A),
                            bgColor: const Color(0xFFDCFCE7),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Total Penarikan',
                            value: '- ${_formatRupiah(totalPenarikan)}',
                            color: const Color(0xFFDC2626),
                            bgColor: const Color(0xFFFEE2E2),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _buildMetricCard(
                            label: 'Saldo Akhir',
                            value: _formatRupiah(saldoAkhir),
                            color: const Color(0xFF2563EB),
                            bgColor: const Color(0xFFDBEAFE),
                          ),
                        ),
                      ],
                    ),
                  ],
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // 2. TARGET TABUNGAN SAYA (Sesuai instruksi: berada di Laporan Bulanan)
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
                              'Kelola target tabungan untuk memotivasi keuangan.',
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
                        child: Text(target != null ? 'Ubah' : 'Pasang Baru'),
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
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: const Column(
                        children: [
                          Icon(Icons.savings_outlined, size: 36, color: Color(0xFF94A3B8)),
                          SizedBox(height: 8),
                          Text(
                            'Belum ada target tabungan yang dipasang.\nPasang target tabungan untuk memantau perkembangan impian Anda.',
                            textAlign: TextAlign.center,
                            style: TextStyle(fontSize: 12, color: Color(0xFF64748B), height: 1.4),
                          ),
                        ],
                      ),
                    ),
                  ] else ...[
                    Container(
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
                              Text(
                                target['judul_target'] ?? 'Target Tabungan',
                                style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w800, color: Color(0xFF0F172A)),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                decoration: BoxDecoration(
                                  color: (target['persen'] ?? 0) >= 100
                                      ? const Color(0xFFDCFCE7)
                                      : const Color(0xFFEFF6FF),
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Text(
                                  (target['persen'] ?? 0) >= 100 ? 'Tercapai' : 'Dalam Proses',
                                  style: TextStyle(
                                    fontSize: 10,
                                    fontWeight: FontWeight.w700,
                                    color: (target['persen'] ?? 0) >= 100
                                        ? const Color(0xFF16A34A)
                                        : const Color(0xFF2563EB),
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 6),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                'Target: ${_formatRupiah(target['nominal_target'] ?? 0)}',
                                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: Color(0xFF2563EB)),
                              ),
                              if (target['target_tanggal'] != null)
                                Text(
                                  'Batas: ${target['target_tanggal']}',
                                  style: const TextStyle(fontSize: 11, color: Color(0xFF64748B)),
                                ),
                            ],
                          ),
                          const SizedBox(height: 10),

                          // Progress Bar
                          ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: LinearProgressIndicator(
                              value: ((target['persen'] ?? 0) / 100).clamp(0.0, 1.0),
                              minHeight: 10,
                              backgroundColor: const Color(0xFFE2E8F0),
                              valueColor: AlwaysStoppedAnimation<Color>(
                                (target['persen'] ?? 0) >= 100
                                    ? const Color(0xFF16A34A)
                                    : const Color(0xFF2563EB),
                              ),
                            ),
                          ),
                          const SizedBox(height: 6),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                'Terkumpul: ${_formatRupiah(saldo)} (${target['persen'] ?? 0}%)',
                                style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF475569)),
                              ),
                              Text(
                                'Sisa: ${_formatRupiah(target['sisa'] ?? 0)}',
                                style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFFDC2626)),
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
                              TextButton.icon(
                                icon: const Icon(Icons.delete_outline, size: 16, color: Color(0xFFDC2626)),
                                label: const Text('Hapus Target', style: TextStyle(color: Color(0xFFDC2626), fontSize: 12)),
                                onPressed: _handleHapusTarget,
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
  // BUILD TAB 2: PROFIL & PENGADUAN
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
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. DATA RESMI SISWA
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
          const SizedBox(height: 16),

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
          const SizedBox(height: 16),

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
                      child: const Column(
                        children: [
                          Icon(Icons.chat_bubble_outline, size: 36, color: Color(0xFF94A3B8)),
                          SizedBox(height: 8),
                          Text(
                            'Belum ada riwayat pengaduan nasabah yang diajukan.',
                            textAlign: TextAlign.center,
                            style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                          ),
                        ],
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
                                                ? const Color(0xFF1E40AF)
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

                              // TANGGAPAN ADMIN
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
  }) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 11, color: Color(0xFF64748B), fontWeight: FontWeight.w600)),
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

  Widget _buildFilterChip({
    required String label,
    required bool isSelected,
    required Color selectedColor,
    required VoidCallback onSelected,
  }) {
    return GestureDetector(
      onTap: onSelected,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 150),
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
        decoration: BoxDecoration(
          color: isSelected ? selectedColor : const Color(0xFFF1F5F9),
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? selectedColor : const Color(0xFFCBD5E1),
          ),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.w700,
            color: isSelected ? Colors.white : const Color(0xFF475569),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final nama = _nasabahData['nama_siswa'] ?? _nasabahData['student_name'] ?? 'Siswa';
    final statusLayanan = (_nasabahData['status_layanan'] ?? 'buka').toString().toLowerCase();

    return Scaffold(
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Portal Nasabah Siswa',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800),
            ),
            Text(
              'Halo, $nama',
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w400),
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
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    color: Colors.white,
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: statusLayanan == 'buka'
                        ? const Color(0xFFDCFCE7)
                        : statusLayanan == 'istirahat'
                            ? const Color(0xFFFEF3C7)
                            : const Color(0xFFFEE2E2),
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Text(
                    statusLayanan == 'buka'
                        ? 'Buka'
                        : statusLayanan == 'istirahat'
                            ? 'Istirahat'
                            : 'Tutup',
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w800,
                      color: statusLayanan == 'buka'
                          ? const Color(0xFF15803D)
                          : statusLayanan == 'istirahat'
                              ? const Color(0xFFB45309)
                              : const Color(0xFFB91C1C),
                    ),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 4),
          IconButton(
            tooltip: 'Keluar',
            icon: const Icon(Icons.logout),
            onPressed: _handleLogout,
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _fetchDashboardData,
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : IndexedStack(
                index: _currentIndex,
                children: [
                  _buildDashboardTab(),
                  _buildLaporanTargetTab(),
                  _buildProfilPengaduanTab(),
                ],
              ),
      ),
      // BOTTOM NAVIGATION BAR: Tetap berada di bawah saat halaman di-scroll
      bottomNavigationBar: Container(
        decoration: const BoxDecoration(
          boxShadow: [
            BoxShadow(
              color: Colors.black12,
              blurRadius: 8,
              offset: Offset(0, -2),
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
              icon: Icon(Icons.account_balance_wallet_outlined),
              activeIcon: Icon(Icons.account_balance_wallet_rounded),
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
// 3. DIALOG PEMULIHAN SANDI AKUN VIA OTP GMAIL (STEP 1 & STEP 2)
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
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFFCBD5E1)),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFFCBD5E1)),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFF2563EB), width: 1.5),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
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
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
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
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
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
                    borderRadius: BorderRadius.circular(12),
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
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
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
