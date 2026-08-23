import 'dart:async';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:go_router/go_router.dart';
import 'package:xwendngakan_app/core/constants/app_constants.dart';
import '../../core/constants/app_colors.dart';
import '../../core/localization/app_localizations.dart';
import '../../data/services/api_service.dart';
import '../../shared/widgets/common_widgets.dart';
import '../../data/models/institution_model.dart';
import 'package:provider/provider.dart';
import '../../providers/locale_provider.dart';

class MapScreen extends StatefulWidget {
  final InstitutionModel? singleInstitution;
  const MapScreen({super.key, this.singleInstitution});

  @override
  State<MapScreen> createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  final Completer<GoogleMapController> _controller = Completer();

  List<Map<String, dynamic>> _pins = [];
  Set<Marker> _markers = {};
  bool _loading = true;
  String? _error;

  // Selected pin for bottom sheet
  Map<String, dynamic>? _selected;

  late CameraPosition _initialPosition;

  @override
  void initState() {
    super.initState();
    if (widget.singleInstitution != null &&
        widget.singleInstitution!.lat != null &&
        widget.singleInstitution!.lng != null) {
      _initialPosition = CameraPosition(
        target: LatLng(
            widget.singleInstitution!.lat!, widget.singleInstitution!.lng!),
        zoom: 15.0,
      );
    } else {
      _initialPosition = const CameraPosition(
        target: LatLng(36.1901, 44.0090), // Erbil center
        zoom: 11.5,
      );
    }
    _loadPins();
  }

  Future<void> _loadPins() async {
    if (widget.singleInstitution != null) {
      final inst = widget.singleInstitution!;
      if (inst.lat != null && inst.lng != null) {
        final pin = {
          'id': inst.id,
          'lat': inst.lat,
          'lng': inst.lng,
          'type': inst.type,
          'nku': inst.nku,
          'nen': inst.nen,
          'city': inst.city,
          'logo': inst.logo,
        };
        _pins = [pin];
        _buildMarkers();
        setState(() {
          _loading = false;
          _selected = pin;
        });
        return;
      }
    }

    setState(() {
      _loading = true;
      _error = null;
    });
    final result = await ApiService().getMapPins();
    if (!mounted) return;

    if (result.success && result.data != null) {
      _pins = result.data!;
      _buildMarkers();
      setState(() => _loading = false);
    } else {
      setState(() {
        _loading = false;
        _error = result.error;
      });
    }
  }

  void _buildMarkers() {
    _markers = _pins.map((pin) {
      final lat = (pin['lat'] as num).toDouble();
      final lng = (pin['lng'] as num).toDouble();
      final type = pin['type'] as String? ?? '';
      final color = _typeToHue(type);

      return Marker(
        markerId: MarkerId('${pin['id']}'),
        position: LatLng(lat, lng),
        icon: BitmapDescriptor.defaultMarkerWithHue(color),
        infoWindow: InfoWindow(
          title: pin['nku'] ?? pin['nen'] ?? '',
          snippet: pin['city'] ?? '',
        ),
        onTap: () {
          setState(() => _selected = pin);
        },
      );
    }).toSet();
  }

  double _typeToHue(String type) {
    switch (type) {
      case 'gov':
        return BitmapDescriptor.hueBlue;
      case 'priv':
        return BitmapDescriptor.hueRose;
      case 'inst5':
      case 'inst2':
        return BitmapDescriptor.hueViolet;
      case 'school':
        return BitmapDescriptor.hueGreen;
      case 'kg':
        return BitmapDescriptor.hueYellow;
      case 'dc':
        return BitmapDescriptor.hueCyan;
      default:
        return BitmapDescriptor.hueRed;
    }
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final l10n = AppLocalizations.of(context);

    final locale = Provider.of<LocaleProvider>(context).locale.languageCode;

    return Scaffold(
      appBar: AppBar(
        title: Text(
          widget.singleInstitution != null
              ? widget.singleInstitution!.name(locale)
              : l10n.institutionMap,
          style:
              const TextStyle(fontFamily: 'Rabar', fontWeight: FontWeight.w800),
        ),
        centerTitle: true,
        backgroundColor: isDark ? AppColors.darkBg : Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded),
            onPressed: _loadPins,
            tooltip: 'تازەکردنەوە',
          ),
        ],
      ),
      body: Stack(
        children: [
          GoogleMap(
            mapType: MapType.normal,
            style: isDark ? _darkMapStyle : null,
            initialCameraPosition: _initialPosition,
            markers: _markers,
            myLocationButtonEnabled: true,
            myLocationEnabled: true,
            zoomControlsEnabled: false,
            mapToolbarEnabled: false,
            onMapCreated: (controller) => _controller.complete(controller),
            onTap: (_) => setState(() => _selected = null),
          ),

          // Loading overlay
          if (_loading)
            Container(
              color: Colors.black26,
              child: const Center(child: CircularProgressIndicator()),
            ),

          // Error
          if (_error != null)
            Center(
              child: Card(
                margin: const EdgeInsets.all(24),
                child: Padding(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(Icons.error_outline,
                          size: 48, color: Colors.red),
                      const SizedBox(height: 12),
                      Text(_error!, textAlign: TextAlign.center),
                      const SizedBox(height: 12),
                      ElevatedButton(
                          onPressed: _loadPins,
                          child: const Text('دووبارە هەوڵبدەرەوە')),
                    ],
                  ),
                ),
              ),
            ),

          // Pin count badge
          if (!_loading && _error == null)
            Positioned(
              top: 12,
              left: 12,
              child: Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(
                  color: isDark ? AppColors.darkCard : Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  boxShadow: const [
                    BoxShadow(color: Colors.black26, blurRadius: 8)
                  ],
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(Icons.location_on,
                        size: 16, color: AppColors.primary),
                    const SizedBox(width: 4),
                    Text(
                      '${_pins.length} دامەزراوە',
                      style: const TextStyle(
                        fontFamily: 'Rabar',
                        fontWeight: FontWeight.bold,
                        fontSize: 13,
                      ),
                    ),
                  ],
                ),
              ),
            ),

          // Selected pin preview
          if (_selected != null)
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: _PinPreviewCard(
                pin: _selected!,
                onClose: () => setState(() => _selected = null),
                onViewDetails: () {
                  setState(() => _selected = null);
                  context.push('/institutions/${_selected!['id']}');
                },
                l10n: l10n,
                isDark: isDark,
              ),
            ),
        ],
      ),
    );
  }
}

class _PinPreviewCard extends StatelessWidget {
  final Map<String, dynamic> pin;
  final VoidCallback onClose;
  final VoidCallback onViewDetails;
  final AppLocalizations l10n;
  final bool isDark;

  const _PinPreviewCard({
    required this.pin,
    required this.onClose,
    required this.onViewDetails,
    required this.l10n,
    required this.isDark,
  });

  @override
  Widget build(BuildContext context) {
    final name = pin['nku'] ?? pin['nen'] ?? '';
    final city = pin['city'] ?? '';
    final type = pin['type'] as String? ?? '';
    final typeColor = AppColors.typeColor(type);
    final logo = pin['logo'] as String?;
    final baseDomain = AppConstants.baseUrl.replaceAll(RegExp(r'/api/?$'), '');
    final logoUrl = logo != null && logo.isNotEmpty
        ? (logo.startsWith('http') ? logo : '$baseDomain/storage/$logo')
        : null;

    return Container(
      margin: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: isDark ? AppColors.darkCard : Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: const [
          BoxShadow(color: Colors.black26, blurRadius: 16, offset: Offset(0, 4))
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle bar
          Padding(
            padding: const EdgeInsets.only(top: 10),
            child: Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                  color: Colors.grey[300],
                  borderRadius: BorderRadius.circular(2)),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Container(
                  width: 56,
                  height: 56,
                  decoration: BoxDecoration(
                    color: typeColor.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  child: logoUrl != null
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(14),
                          child: Image.network(logoUrl,
                              fit: BoxFit.cover,
                              errorBuilder: (_, __, ___) => Icon(Icons.school,
                                  color: typeColor, size: 28)),
                        )
                      : Icon(Icons.school, color: typeColor, size: 28),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(name,
                          style: const TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              fontFamily: 'Rabar'),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          Icon(Icons.location_city_rounded,
                              size: 14, color: Colors.grey[500]),
                          const SizedBox(width: 4),
                          Text(city,
                              style: TextStyle(
                                  color: Colors.grey[500],
                                  fontFamily: 'Rabar',
                                  fontSize: 13)),
                        ],
                      ),
                    ],
                  ),
                ),
                IconButton(icon: const Icon(Icons.close), onPressed: onClose),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
            child: GradientButton(
                text: l10n.viewDetails, onPressed: onViewDetails),
          ),
        ],
      ),
    );
  }
}

// Dark mode map style (Google Maps JSON style)
const String _darkMapStyle = '''[
  {"elementType":"geometry","stylers":[{"color":"#212121"}]},
  {"elementType":"labels.icon","stylers":[{"visibility":"off"}]},
  {"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},
  {"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},
  {"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},
  {"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},
  {"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},
  {"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},
  {"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},
  {"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},
  {"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},
  {"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},
  {"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},
  {"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},
  {"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},
  {"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},
  {"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},
  {"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},
  {"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},
  {"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}
]''';
