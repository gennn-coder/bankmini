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
                          'Welcome Back!',
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
                              'Lupa Sandi??',
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
// 2. HALAMAN DASHBOARD NASABAH
// ---------------------------------------------------------------------------
class DashboardScreen extends StatefulWidget {
  final Map<String, dynamic> nasabah;

  const DashboardScreen({super.key, required this.nasabah});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  late Map<String, dynamic> _nasabahData;
  List<dynamic> _mutasiList = [];
  bool _isLoading = true;
  bool _isRefreshing = false;
  Timer? _statusPollingTimer;
  String _filterMutasi = 'all'; // 'all', 'setoran', 'penarikan'
  final TextEditingController _searchMutasiController = TextEditingController();
  String _mutasiSearchQuery = '';

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
    // Auto sync status teller & saldo real-time setiap 4 detik (sama dengan web)
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
    super.dispose();
  }

  Future<void> _fetchStatusSilent() async {
    final nasabahId = _nasabahData['nasabah_id'] ?? _nasabahData['id'];
    try {
      final res = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/dashboard/$nasabahId'))
          .timeout(const Duration(seconds: 4));
      if (res.statusCode == 200 && mounted) {
        final dataDash = jsonDecode(res.body);
        if (dataDash['success'] == true && dataDash['data'] != null) {
          setState(() {
            _nasabahData = dataDash['data'];
          });
        }
      }
    } catch (_) {}
  }

  Future<void> _fetchDashboardData() async {
    final nasabahId = _nasabahData['nasabah_id'] ?? _nasabahData['id'];

    try {
      // 1. Fetch Profile & Saldo
      final resDash = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/dashboard/$nasabahId'))
          .timeout(const Duration(seconds: 8));

      if (resDash.statusCode == 200) {
        final dataDash = jsonDecode(resDash.body);
        if (dataDash['success'] == true) {
          setState(() {
            _nasabahData = dataDash['data'];
          });
        }
      }

      // 2. Fetch Mutasi Transaksi
      final resMutasi = await http
          .get(Uri.parse('${ApiConfig.baseUrl}/mutasi/$nasabahId'))
          .timeout(const Duration(seconds: 8));

      if (resMutasi.statusCode == 200) {
        final dataMutasi = jsonDecode(resMutasi.body);
        if (dataMutasi['success'] == true) {
          setState(() {
            _mutasiList = dataMutasi['data'];
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

  String _formatRupiah(num number) {
    final str = number.toStringAsFixed(0);
    final reg = RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))');
    final result = str.replaceAllMapped(reg, (Match m) => '${m[1]}.');
    return 'Rp $result';
  }

  void _showQrModal() {
    final noRek = _nasabahData['no_rekening'] ?? '-';
    final nama = _nasabahData['nama_siswa'] ?? '-';
    final kelas = _nasabahData['kelas'] ?? '-';
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

                // Container QR Image
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFBFDBFE), width: 2),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.blue.withOpacity(0.08),
                        blurRadius: 16,
                        offset: const Offset(0, 4),
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

  @override
  Widget build(BuildContext context) {
    final nama = _nasabahData['nama_siswa'] ?? 'Siswa';
    final noRek = _nasabahData['no_rekening'] ?? '-';
    final kelas = _nasabahData['kelas'] ?? '-';
    final nis = _nasabahData['nis'] ?? '-';
    final saldo = _nasabahData['saldo'] ?? 0;
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
            : SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    // CARD 1: INFORMASI REKENING
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
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 8, vertical: 2),
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
                                      'Kelas / NIS',
                                      style: TextStyle(
                                          fontSize: 11, color: Color(0xFF64748B)),
                                    ),
                                    Text(
                                      '$kelas ($nis)',
                                      style: const TextStyle(
                                        fontSize: 13,
                                        fontWeight: FontWeight.w700,
                                        color: Color(0xFF0F172A),
                                      ),
                                    ),
                                  ],
                                ),
                                OutlinedButton(
                                  onPressed: _showQrModal,
                                  style: OutlinedButton.styleFrom(
                                    side: const BorderSide(color: Color(0xFFBFDBFE)),
                                    backgroundColor: const Color(0xFFEFF6FF),
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 12, vertical: 6),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                  ),
                                  child: const Text(
                                    'Lihat Kode QR',
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

                    // CARD 2: SALDO REAL-TIME
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
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),

                    // TOMBOL UTAMA TAMPILKAN QR KARTU DIGITAL
                    ElevatedButton(
                      onPressed: _showQrModal,
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 14),
                      ),
                      child: const Text(
                        'Tampilkan Kode QR Kartu Digital Nasabah',
                        textAlign: TextAlign.center,
                      ),
                    ),
                    const SizedBox(height: 20),

                    // SECTION RIWAYAT MUTASI REKENING
                    const Text(
                      'Riwayat Mutasi Rekening Siswa',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w800,
                        color: Color(0xFF0F172A),
                      ),
                    ),
                    const SizedBox(height: 4),
                    const Text(
                      'Daftar riwayat transaksi setoran dan penarikan yang pernah dilakukan.',
                      style: TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                    ),
                    const SizedBox(height: 12),

                    // FILTER & PENCARIAN MUTASI
                    if (_mutasiList.isNotEmpty) ...[
                      // Filter Chips Row
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

                      // Input Cari Transaksi
                      TextField(
                        controller: _searchMutasiController,
                        onChanged: (val) {
                          setState(() {
                            _mutasiSearchQuery = val;
                          });
                        },
                        style: const TextStyle(fontSize: 13),
                        decoration: InputDecoration(
                          hintText: 'Cari no transaksi, tanggal, nominal...',
                          hintStyle: const TextStyle(fontSize: 12, color: Color(0xFF94A3B8)),
                          prefixIcon: const Icon(Icons.search, size: 18, color: Color(0xFF64748B)),
                          suffixIcon: _mutasiSearchQuery.isNotEmpty
                              ? IconButton(
                                  icon: const Icon(Icons.clear, size: 16, color: Color(0xFF94A3B8)),
                                  onPressed: () {
                                    _searchMutasiController.clear();
                                    setState(() {
                                      _mutasiSearchQuery = '';
                                    });
                                  },
                                )
                              : null,
                          isDense: true,
                          contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                          fillColor: Colors.white,
                          filled: true,
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
                            borderSide: const BorderSide(color: Color(0xFF2563EB)),
                          ),
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
                                Icon(Icons.receipt_long_outlined,
                                    size: 40, color: Color(0xFF94A3B8)),
                                SizedBox(height: 8),
                                Text(
                                  'Belum ada riwayat transaksi mutasi pada rekening ini.',
                                  textAlign: TextAlign.center,
                                  style: TextStyle(
                                    fontSize: 12,
                                    color: Color(0xFF64748B),
                                  ),
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
                                Icon(Icons.filter_alt_off_outlined,
                                    size: 36, color: Color(0xFF94A3B8)),
                                SizedBox(height: 8),
                                Text(
                                  'Tidak ada transaksi yang cocok dengan filter atau kata kunci pencarian.',
                                  textAlign: TextAlign.center,
                                  style: TextStyle(
                                    fontSize: 12,
                                    color: Color(0xFF64748B),
                                  ),
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
                        separatorBuilder: (context, index) =>
                            const SizedBox(height: 8),
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
                                  // Icon Indikator Jenis Transaksi
                                  Container(
                                    padding: const EdgeInsets.all(10),
                                    decoration: BoxDecoration(
                                      color: isSetoran
                                          ? const Color(0xFFDCFCE7)
                                          : const Color(0xFFFEE2E2),
                                      borderRadius: BorderRadius.circular(10),
                                    ),
                                    child: Icon(
                                      isSetoran
                                          ? Icons.arrow_downward
                                          : Icons.arrow_upward,
                                      color: isSetoran
                                          ? const Color(0xFF16A34A)
                                          : const Color(0xFFDC2626),
                                      size: 20,
                                    ),
                                  ),
                                  const SizedBox(width: 12),

                                  // Detail Transaksi
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                          children: [
                                            Text(
                                              isSetoran
                                                  ? 'SETORAN'
                                                  : 'PENARIKAN',
                                              style: TextStyle(
                                                fontSize: 12,
                                                fontWeight: FontWeight.w800,
                                                color: isSetoran
                                                    ? const Color(0xFF16A34A)
                                                    : const Color(0xFFDC2626),
                                              ),
                                            ),
                                            Text(
                                              '${isSetoran ? '+' : '-'} ${_formatRupiah(nominal)}',
                                              style: TextStyle(
                                                fontSize: 13,
                                                fontWeight: FontWeight.w800,
                                                color: isSetoran
                                                    ? const Color(0xFF16A34A)
                                                    : const Color(0xFFDC2626),
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
                                          style: const TextStyle(
                                            fontSize: 11,
                                            color: Color(0xFF94A3B8),
                                          ),
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
              ),
      ),
    );
  }
}

// ---------------------------------------------------------------------------
// DIALOG PEMULIHAN SANDI AKUN VIA OTP GMAIL (STEP 1 & STEP 2)
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
              // Header Row
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

              // Subtitle
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

              // Alert Banner
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
                // STEP 1 UI
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

                // Button Kirim Kode OTP
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

                // Button Selanjutnya
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
                // STEP 2 UI
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

                // Button Simpan Sandi Baru
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
