<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/album' => [[['_route' => 'app_album_album_seguido', '_controller' => 'App\\Controller\\AlbumController::album_seguido'], null, null, null, false, false, null]],
        '/pago' => [[['_route' => 'app_pago_usuario_pagos', '_controller' => 'App\\Controller\\PagoController::usuario_pagos'], null, null, null, false, false, null]],
        '/suscripcion' => [[['_route' => 'app_suscripcion_usuario_suscripcion', '_controller' => 'App\\Controller\\SuscripcionController::usuario_suscripcion'], null, null, null, false, false, null]],
        '/usuarios' => [[['_route' => 'usuarios', '_controller' => 'App\\Controller\\UsuarioController::usuarios'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/canciones' => [[['_route' => 'canciones', '_controller' => 'App\\Controller\\CancionController::canciones'], null, ['GET' => 0], null, false, false, null]],
        '/artistas' => [[['_route' => 'artistas', '_controller' => 'App\\Controller\\ArtistaController::artistas'], null, ['GET' => 0], null, false, false, null]],
        '/albums' => [[['_route' => 'albums', '_controller' => 'App\\Controller\\AlbumController::albums'], null, ['GET' => 0], null, false, false, null]],
        '/podcasts' => [[['_route' => 'podcasts', '_controller' => 'App\\Controller\\PodcastController::podcasts'], null, ['GET' => 0], null, false, false, null]],
        '/playlists' => [[['_route' => 'playlists', '_controller' => 'App\\Controller\\PlaylistController::playlists'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:102)'
                            .'|router(*:116)'
                            .'|exception(?'
                                .'|(*:136)'
                                .'|\\.css(*:149)'
                            .')'
                        .')'
                        .'|(*:159)'
                    .')'
                .')'
                .'|/usuario(?'
                    .'|/([^/]++)(*:189)'
                    .'|s/([^/]++)/(?'
                        .'|p(?'
                            .'|la(?'
                                .'|n(*:221)'
                                .'|ylists(?'
                                    .'|(*:238)'
                                    .'|\\-seguidas(?'
                                        .'|(*:259)'
                                        .'|/([^/]++)(*:276)'
                                    .')'
                                .')'
                            .')'
                            .'|remium(*:293)'
                            .'|agos(*:305)'
                            .'|odcasts\\-seguidos(?'
                                .'|(*:333)'
                                .'|/([^/]++)(*:350)'
                            .')'
                        .')'
                        .'|c(?'
                            .'|onfiguracion(*:376)'
                            .'|anciones\\-guardadas(?'
                                .'|(*:406)'
                                .'|/([^/]++)(*:423)'
                            .')'
                        .')'
                        .'|a(?'
                            .'|rtistas\\-seguidos(?'
                                .'|(*:457)'
                                .'|/([^/]++)(*:474)'
                            .')'
                            .'|lbums\\-seguidos(?'
                                .'|(*:501)'
                                .'|/([^/]++)(*:518)'
                            .')'
                        .')'
                    .')'
                .')'
                .'|/suscripciones/([^/]++)(*:553)'
                .'|/p(?'
                    .'|laylists/([^/]++)(?'
                        .'|(*:586)'
                        .'|/canciones(?'
                            .'|(*:607)'
                            .'|/([^/]++)(*:624)'
                        .')'
                    .')'
                    .'|odcasts/([^/]++)(?'
                        .'|(*:653)'
                        .'|/capitulos(*:671)'
                    .')'
                .')'
                .'|/ca(?'
                    .'|nciones/([^/]++)(*:703)'
                    .'|pitulos/([^/]++)(*:727)'
                .')'
                .'|/a(?'
                    .'|rtistas/([^/]++)(?'
                        .'|(*:760)'
                        .'|/(?'
                            .'|canciones(*:781)'
                            .'|albums(*:795)'
                        .')'
                    .')'
                    .'|lbums/([^/]++)(?'
                        .'|(*:822)'
                        .'|/canciones(*:840)'
                    .')'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        102 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        116 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        136 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        149 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        159 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        189 => [[['_route' => 'usuario', '_controller' => 'App\\Controller\\UsuarioController::usuario'], ['id'], ['GET' => 0, 'PUT' => 1, 'DELETE' => 2], null, false, true, null]],
        221 => [[['_route' => 'usuario_plan', '_controller' => 'App\\Controller\\PlanController::usuario_plan'], ['id'], ['GET' => 0], null, false, false, null]],
        238 => [[['_route' => 'playlists_usuario', '_controller' => 'App\\Controller\\PlaylistController::playlist_usuario'], ['userId'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        259 => [[['_route' => 'follow_playlists', '_controller' => 'App\\Controller\\PlaylistController::playlist_seguidas'], ['userId'], ['GET' => 0], null, false, false, null]],
        276 => [[['_route' => 'follow_playlist', '_controller' => 'App\\Controller\\PlaylistController::playlist_seguida'], ['userId', 'playlistId'], ['PUT' => 0, 'DELETE' => 1], null, false, true, null]],
        293 => [[['_route' => 'usuario_premium', '_controller' => 'App\\Controller\\PlanController::usuario_premium'], ['id'], ['POST' => 0], null, false, false, null]],
        305 => [[['_route' => 'usuario_pagos', '_controller' => 'App\\Controller\\PagoController::usuario_pagos'], ['id'], ['GET' => 0], null, false, false, null]],
        333 => [[['_route' => 'follow_podcasts', '_controller' => 'App\\Controller\\PodcastController::podcasts_seguidos'], ['userId'], ['GET' => 0], null, false, false, null]],
        350 => [[['_route' => 'follow_podcast', '_controller' => 'App\\Controller\\PodcastController::podcast_seguido'], ['userId', 'podcastId'], ['PUT' => 0, 'DELETE' => 1], null, false, true, null]],
        376 => [[['_route' => 'configuracion_usuario', '_controller' => 'App\\Controller\\ConfiguracionController::configuracion_usuario'], ['userId'], ['GET' => 0, 'PUT' => 1], null, false, false, null]],
        406 => [[['_route' => 'canciones_guardadas', '_controller' => 'App\\Controller\\CancionController::canciones_guardadas'], ['userId'], ['GET' => 0], null, false, false, null]],
        423 => [[['_route' => 'cancion_guardada', '_controller' => 'App\\Controller\\CancionController::cancion_guardada'], ['userId', 'cancionId'], ['PUT' => 0, 'DELETE' => 1], null, false, true, null]],
        457 => [[['_route' => 'follow_artistas', '_controller' => 'App\\Controller\\ArtistaController::artistas_seguidos'], ['userId'], ['GET' => 0], null, false, false, null]],
        474 => [[['_route' => 'follow_artista', '_controller' => 'App\\Controller\\ArtistaController::artista_seguido'], ['userId', 'artistaId'], ['PUT' => 0, 'DELETE' => 1], null, false, true, null]],
        501 => [[['_route' => 'follow_albums', '_controller' => 'App\\Controller\\AlbumController::albums_seguidos'], ['userId'], ['GET' => 0], null, false, false, null]],
        518 => [[['_route' => 'follow_album', '_controller' => 'App\\Controller\\AlbumController:album_seguido'], ['userId', 'albumId'], ['PUT' => 0, 'DELETE' => 1], null, false, true, null]],
        553 => [[['_route' => 'usuario_suscipciones', '_controller' => 'App\\Controller\\SuscripcionController::usuario_suscripcion'], ['id'], ['GET' => 0], null, false, true, null]],
        586 => [[['_route' => 'playlist', '_controller' => 'App\\Controller\\PlaylistController::playlist'], ['playlistId'], ['GET' => 0], null, false, true, null]],
        607 => [[['_route' => 'playlist_cancion', '_controller' => 'App\\Controller\\PlaylistController::playlist_cancion'], ['playlistId'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        624 => [[['_route' => 'playlist_cancion_delete', '_controller' => 'App\\Controller\\PlaylistController::playlist_delete_cancion'], ['playlistId', 'cancionId'], ['DELETE' => 0], null, false, true, null]],
        653 => [[['_route' => 'podcast', '_controller' => 'App\\Controller\\PodcastController::podcast'], ['podcastId'], ['GET' => 0], null, false, true, null]],
        671 => [[['_route' => 'podcast_capitulos', '_controller' => 'App\\Controller\\PodcastController::podcast_capitulos'], ['podcastId'], ['GET' => 0], null, false, false, null]],
        703 => [[['_route' => 'cancion', '_controller' => 'App\\Controller\\CancionController::cancion'], ['cancionId'], ['GET' => 0], null, false, true, null]],
        727 => [[['_route' => 'capitulo', '_controller' => 'App\\Controller\\CapituloController::Capitulo'], ['capituloId'], ['GET' => 0], null, false, true, null]],
        760 => [[['_route' => 'artista', '_controller' => 'App\\Controller\\ArtistaController::artista'], ['artistaId'], ['GET' => 0], null, false, true, null]],
        781 => [[['_route' => 'artistas_canciones', '_controller' => 'App\\Controller\\ArtistaController::artista_caniones'], ['artistaId'], ['GET' => 0], null, false, false, null]],
        795 => [[['_route' => 'artista_albums', '_controller' => 'App\\Controller\\ArtistaController::artista_albums'], ['artistaId'], ['GET' => 0], null, false, false, null]],
        822 => [[['_route' => 'album', '_controller' => 'App\\Controller\\AlbumController::album'], ['albumId'], ['GET' => 0], null, false, true, null]],
        840 => [
            [['_route' => 'albums_canciones', '_controller' => 'App\\Controller\\AlbumController::album_canciones'], ['albumId'], ['GET' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
