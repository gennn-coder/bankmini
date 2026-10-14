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
  // Bekerja langsung via USB dengan adb reverse, atau ganti ke IP Wi-Fi via tombol pengaturan di login
  static String baseUrl = 'http://127.0.0.1:8000/api/nasabah';
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

  void _showServerConfigDialog() {
    final TextEditingController urlController =
        TextEditingController(text: ApiConfig.baseUrl);

    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Pengaturan URL Server'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Sesuaikan alamat backend Laravel sesuai perangkat uji coba Anda:',
                style: TextStyle(fontSize: 13, color: Color(0xFF64748B)),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: urlController,
                decoration: const InputDecoration(
                  labelText: 'Base URL API',
                  hintText: 'http://10.0.2.2:8000/api/nasabah',
                ),
              ),
              const SizedBox(height: 8),
              const Text(
                '• Emulator Android: http://10.0.2.2:8000/api/nasabah\n• Chrome/Web/Desktop: http://127.0.0.1:8000/api/nasabah\n• HP Fisik via Wi-Fi: http://[IP_Komputer]:8000/api/nasabah',
                style: TextStyle(fontSize: 11, color: Color(0xFF64748B)),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () {
                setState(() {
                  ApiConfig.baseUrl = urlController.text.trim();
                });
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('URL Server berhasil diperbarui.')),
                );
              },
              child: const Text('Simpan'),
            ),
          ],
        );
      },
    );
  }

  void _showLupaSandiDialog() {
    final TextEditingController emailController = TextEditingController();
    bool isSubmitting = false;

    showDialog(
      context: context,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Text('Pemulihan Lupa Sandi'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Masukkan alamat Gmail Nasabah Anda yang terdaftar pada sistem E-Teller Bank Mini.',
                    style: TextStyle(fontSize: 13, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 14),
                  TextField(
                    controller: emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: const InputDecoration(
                      labelText: 'Alamat Gmail Nasabah',
                      hintText: 'ahmad.rizky@gmail.com',
                    ),
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Tutup'),
                ),
                ElevatedButton(
                  onPressed: isSubmitting
                      ? null
                      : () async {
                          final email = emailController.text.trim();
                          if (email.isEmpty) return;

                          setDialogState(() => isSubmitting = true);
                          try {
                            final res = await http.post(
                              Uri.parse('${ApiConfig.baseUrl}/lupa-sandi'),
                              headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                              },
                              body: jsonEncode({'email': email}),
                            );
                            final data = jsonDecode(res.body);
                            if (mounted) {
                              Navigator.pop(context);
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(
                                  content: Text(
                                    data['message'] ?? 'Petunjuk pemulihan kata sandi telah dikirimkan.',
                                  ),
                                ),
                              );
                            }
                          } catch (e) {
                            if (mounted) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(content: Text('Gagal mengirim: $e')),
                              );
                            }
                          } finally {
                            setDialogState(() => isSubmitting = false);
                          }
                        },
                  child: isSubmitting
                      ? const SizedBox(
                          width: 16,
                          height: 16,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: Colors.white,
                          ),
                        )
                      : const Text('Kirim ke Gmail'),
                ),
              ],
            );
          },
        );
      },
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

                // Server Config Link & Footer
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    TextButton.icon(
                      onPressed: _showServerConfigDialog,
                      icon: const Icon(Icons.settings_outlined, size: 16),
                      label: const Text(
                        'Konfigurasi Server API',
                        style: TextStyle(fontSize: 12),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
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

  @override
  void initState() {
    super.initState();
    _nasabahData = widget.nasabah;
    _fetchDashboardData();
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

                    if (_mutasiList.isEmpty)
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(28),
                          child: Center(
                            child: Column(
                              children: const [
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
                    else
                      ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _mutasiList.length,
                        separatorBuilder: (context, index) =>
                            const SizedBox(height: 8),
                        itemBuilder: (context, index) {
                          final item = _mutasiList[index];
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
