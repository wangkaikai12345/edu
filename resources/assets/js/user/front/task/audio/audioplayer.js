/*
 * Author: Audio Player with Playlist
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://bit.ly/nM4R6u
 * Version: 2.74
 * */


function htmlEncode(arg) {
    return jQuery('<div/>').text(arg).html();
}

function htmlDecode(value) {
    return jQuery('<div/>').html(arg).text();
}


var dzsap_list = [];
var dzsap_ytapiloaded = false;
var dzsap_globalidind = 20;

var dzsap_list_for_sync_players = [];
var dzsap_list_for_sync_sw_build = false;
var dzsap_list_for_sync_inter_build = 0;


window.dzsap_player_interrupted_by_dzsvg = null;
window.dzsap_audio_ctx = null;

window.dzsap_self_options = {};

window.dzsap_generating_pcm = false;


window.dzsap_player_index = 0; // -- the player index on the page



(function($) {




    window.dzsap_list_for_sync_build = function() {


    };


    var svg_play_icon = '<svg class="svg-icon" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="11.161px" height="12.817px" viewBox="0 0 11.161 12.817" enable-background="new 0 0 11.161 12.817" xml:space="preserve"> <g> <g> <g> <path fill="#D2D6DB" d="M8.233,4.589c1.401,0.871,2.662,1.77,2.801,1.998c0.139,0.228-1.456,1.371-2.896,2.177l-4.408,2.465 c-1.44,0.805-2.835,1.474-3.101,1.484c-0.266,0.012-0.483-1.938-0.483-3.588V3.666c0-1.65,0.095-3.19,0.212-3.422 c0.116-0.232,1.875,0.613,3.276,1.484L8.233,4.589z"/> </g> </g> </g> </svg>  ';

    var svg_heart_icon = '<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" width="15" height="15"  viewBox="0 0 645 700" id="svg2"> <defs id="defs4" /> <g id="layer1"> <path d="M 297.29747,550.86823 C 283.52243,535.43191 249.1268,505.33855 220.86277,483.99412 C 137.11867,420.75228 125.72108,411.5999 91.719238,380.29088 C 29.03471,322.57071 2.413622,264.58086 2.5048478,185.95124 C 2.5493594,147.56739 5.1656152,132.77929 15.914734,110.15398 C 34.151433,71.768267 61.014996,43.244667 95.360052,25.799457 C 119.68545,13.443675 131.6827,7.9542046 172.30448,7.7296236 C 214.79777,7.4947896 223.74311,12.449347 248.73919,26.181459 C 279.1637,42.895777 310.47909,78.617167 316.95242,103.99205 L 320.95052,119.66445 L 330.81015,98.079942 C 386.52632,-23.892986 564.40851,-22.06811 626.31244,101.11153 C 645.95011,140.18758 648.10608,223.6247 630.69256,270.6244 C 607.97729,331.93377 565.31255,378.67493 466.68622,450.30098 C 402.0054,497.27462 328.80148,568.34684 323.70555,578.32901 C 317.79007,589.91654 323.42339,580.14491 297.29747,550.86823 z" id="path2417" style="" /> <g transform="translate(129.28571,-64.285714)" id="g2221" /> </g> </svg> ';


    var svg_share_icon = '<svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 511.626 511.627" style="enable-background:new 0 0 511.626 511.627;" xml:space="preserve"> <g> <path d="M506.206,179.012L360.025,32.834c-3.617-3.617-7.898-5.426-12.847-5.426s-9.233,1.809-12.847,5.426 c-3.617,3.619-5.428,7.902-5.428,12.85v73.089h-63.953c-135.716,0-218.984,38.354-249.823,115.06C5.042,259.335,0,291.03,0,328.907 c0,31.594,12.087,74.514,36.259,128.762c0.57,1.335,1.566,3.614,2.996,6.849c1.429,3.233,2.712,6.088,3.854,8.565 c1.146,2.471,2.384,4.565,3.715,6.276c2.282,3.237,4.948,4.859,7.994,4.859c2.855,0,5.092-0.951,6.711-2.854 c1.615-1.902,2.424-4.284,2.424-7.132c0-1.718-0.238-4.236-0.715-7.569c-0.476-3.333-0.715-5.564-0.715-6.708 c-0.953-12.938-1.429-24.653-1.429-35.114c0-19.223,1.668-36.449,4.996-51.675c3.333-15.229,7.948-28.407,13.85-39.543 c5.901-11.14,13.512-20.745,22.841-28.835c9.325-8.09,19.364-14.702,30.118-19.842c10.756-5.141,23.413-9.186,37.974-12.135 c14.56-2.95,29.215-4.997,43.968-6.14s31.455-1.711,50.109-1.711h63.953v73.091c0,4.948,1.807,9.232,5.421,12.847 c3.62,3.613,7.901,5.424,12.847,5.424c4.948,0,9.232-1.811,12.854-5.424l146.178-146.183c3.617-3.617,5.424-7.898,5.424-12.847 C511.626,186.92,509.82,182.636,506.206,179.012z" fill="#696969"/> </g></svg> ';



    function hexToRgb(hex, palpha) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        var str = '';
        if (result) {
            result = {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            };



            var alpha = 1;

            if (palpha) {
                alpha = palpha;
            }



            str = 'rgba(' + result.r + ',' + result.g + ',' + result.b + ',' + alpha + ')';
        }


        // console.info('hexToRgb ( hex - '+hex+' ) result ', str);

        return str;


    }



    $.fn.prependOnce = function(arg, argfind) {
        var _t = $(this) // It's your element


        //        console.info(argfind);
        if (typeof(argfind) == 'undefined') {
            var regex = new RegExp('class="(.*?)"');
            var auxarr = regex.exec(arg);


            if (typeof auxarr[1] != 'undefined') {
                argfind = '.' + auxarr[1];
            }
        }


        // we compromise chaining for returning the success
        if (_t.children(argfind).length < 1) {
            _t.prepend(arg);
            return true;
        } else {
            return false;
        }
    };
    $.fn.appendOnce = function(arg, argfind) {
        var _t = $(this) // It's your element


        if (typeof(argfind) == 'undefined') {
            var regex = new RegExp('class="(.*?)"');
            var auxarr = regex.exec(arg);


            if (typeof auxarr[1] != 'undefined') {
                argfind = '.' + auxarr[1];
            }
        }
        // we compromise chaining for returning the success
        if (_t.children(argfind).length < 1) {
            _t.append(arg);
            return true;
        } else {
            return false;
        }
    };
    $.fn.audioplayer = function(o) {
        var defaults = {
            design_skin: 'skin-default' // -- the skin of the player - can be set from the html as well
            ,autoplay: 'off' // -- autoplay the track ( only works is cue is set to "on"
            ,cue: 'on' // this chooses wheter "on" or not "off" a part .. what part is decided by the preload_method below
            ,preload_method: 'metadata' // -- "none" or "metadata" or "auto" ( whole track )
            ,loop: 'off' // -- loop the track
            ,swf_location: "ap.swf" // -- the location of the flash backup
            ,swffull_location: "apfull.swf" // -- the location of the flash backup
            ,settings_backup_type: 'light' // == light or full

            , settings_extrahtml: '' // -- some extra html - can be rates, plays, likes
            ,settings_extrahtml_in_float_left: '' // -- some extra html that you may want to add inside the player, to the right
            , settings_extrahtml_in_float_right: '' // -- some extra html that you may want to add inside the player, to the right
            , settings_extrahtml_before_play_pause: '' // -- some extra html that you may want before play button
            , settings_extrahtml_after_play_pause: '' // -- some extra html that you may want after play button

            ,settings_trigger_resize: '0' // -- check the player dimensions every x milli seconds
            , design_thumbh: "default" //thumbnail size
            ,design_thumbw: "200" // -- thumb width
            ,disable_volume: 'default',
            disable_scrub: 'default',
            disable_player_navigation: 'off'
            ,disable_timer: 'default' // -- disable timer display
            ,type: 'audio'
            ,enable_embed_button: 'off' // -- enable the embed button
            ,embed_code: '' // -- embed code
            ,skinwave_dynamicwaves: 'off' // -- dynamic scale based on volume for no spectrum wave
            ,soundcloud_apikey: '' // -- set the sound cloud api key
            ,parentgallery: null // -- the parent gallery of the player
            ,skinwave_enableSpectrum: 'off' // off or on
            ,skinwave_enableReflect: 'on'  // -- (deprecated)
            ,skinwave_place_thumb_after_volume: 'off' // -- place the thumbnail after volume button
            ,skinwave_place_metaartist_after_volume: 'off' // -- place metaartist after volume button
            ,settings_useflashplayer: 'auto' // off or on or auto
            ,skinwave_spectrummultiplier: '1' // -- (deprecated) number
            ,settings_php_handler: '' // -- the path of the publisher.php file, this is used to handle comments, likes etc.
            ,php_retriever: 'soundcloudretriever.php' // -- the soundcloud php file used to render soundcloud files
            ,skinwave_mode: 'normal' // --- "normal" or "small"
            ,skinwave_wave_mode: 'canvas' // --- "normal" or "canvas"
            ,skinwave_wave_mode_canvas_mode: 'normal' // --- "normal" or "reflecto"
            ,skinwave_wave_mode_canvas_waves_number: '3' // --- the number of waves in the canvas
            ,skinwave_wave_mode_canvas_waves_padding: '1' // --- padding between waves
            ,skinwave_wave_mode_canvas_reflection_size: '0.25' // --- the reflection size
            ,change_media_animate_skinwave_mode_small: 'off' // --- the reflection size
            ,pcm_data_try_to_generate: 'off' // --- try to find out the pcm data and sent it via ajax ( maybe send it via php_handler
            ,pcm_notice: 'off' // --- show a notice for pcm

            ,skinwave_comments_links_to: '' // -- clicking the comments bar will lead to this link ( optional )
            ,skinwave_comments_enable: 'off' // -- enable the comments, publisher.php must be in the same folder as this html
            ,skinwave_comments_playerid: '',
            skinwave_comments_account: 'none',
            skinwave_comments_process_in_php: 'on' // -- select wheter the comment text should be processed in javascript "off" / or in php, later "on"
            ,skinwave_comments_retrievefromajax: 'off' // --- retrieve the comment form ajax
            ,skinwave_preloader_code: 'default' // --- retrieve the comment form ajax
            ,skinwave_comments_displayontime: 'on' // --- display the comment when the scrub header is over it
            ,skinwave_comments_avatar: 'http://www.gravatar.com/avatar/00000000000000000000000000000000?s=20' // -- default image
            ,skinwave_comments_allow_post_if_not_logged_in: 'on' // -- allow posting in comments section if not looged in

            ,skinwave_timer_static: 'off'
            , default_volume: 'default' // -- number / set the default volume 0-1 or "last" for the last known volume
            , volume_from_gallery: '' // -- the volume set from the gallery item select, leave blank if the player is not called from the gallery
            ,
            design_menu_show_player_state_button: 'off' // -- show a button that allows to hide or show the menu
            ,
            playfrom: 'off' //off or specific number of settings or set to "last"
            ,
            scrubbar_tweak_overflow_hidden: 'off' // -- replace overflow hidden that is used for  with a
            ,design_animateplaypause: 'default'
            ,embedded: 'off' // // -- if embedded in a iframe
            ,embedded_iframe_id: '' // // -- if embedded in a iframe, specify the iframe id here
            ,sample_time_start: '0' // --- if this is a sample to a complete song, you can write here start times, if not, leave to 0.
            ,sample_time_end: '0' // -- if this is a sample to a complete song, you can write here start times, if not, leave to 0.
            ,sample_time_total: '0' // -- if this is a sample to a complete song, you can write here start times, if not, leave to 0.
            ,
            google_analytics_send_play_event: 'off' // -- send the play event to google analytics, you need to have google analytics script already on your page
            ,fakeplayer: null // -- if this is a fake player, it will feed
            ,failsafe_repair_media_element: '' // -- some scripts might effect the media element used by zoomsounds, this is how we replace the media element in a certain time
            ,action_audio_play: null // -- set a outer play function ( for example for tracking your analytics )
            ,action_audio_play2: null // -- set a outer play function ( for example for tracking your analytics )
            ,action_audio_end: null // -- set a outer ended function ( for example for tracking your analytics )
            ,action_audio_comment: null // -- set a outer commented function ( for example for tracking your analytics )
            ,action_audio_change_media: null // -- set a outer on change track function
            ,action_audio_loaded_metadata: null // -- set a outer commented function ( for example for tracking your analytics )
            ,type_audio_stop_buffer_on_unfocus: 'off' // -- if set to on, when the audio player goes out of focus, it will unbuffer the file so that it will not load anymore, useful if you want to stop buffer on large files
            ,construct_player_list_for_sync: 'off' // -- construct a player list from the page that features single players playing one after another. searches for the .is-single-player class in the DOM


            , settings_exclude_from_list: 'off' // -- a audioplayer list is formed at runtime so that when
            , design_wave_color_bg: '222222' // -- waveform background color..  000000,ffffff gradient is allowed
            , design_wave_color_progress: 'ea8c52' // -- waveform progress color


            ,skin_minimal_button_size: '100'
            ,preview_on_hover: 'off' // -- on mouseenter autoplay the track
            ,watermark_volume: '1' // -- on mouseenter autoplay the track
        };



        //console.info(o);
        if (typeof o == 'undefined') {
            if ( $(this).attr('data-options')) {

                var aux = $(this).attr('data-options');
                aux = 'window.dzsap_self_options  = ' + aux;
                try{

                    eval(aux);
                }catch(err){
                    console.warn('eval error',err);
                }
                // console.info($(this), $(this).attr('data-options'), window.dzsap_self_options);
                o = $.extend({}, window.dzsap_self_options);
                window.window.dzsap_self_options = $.extend({}, {});
            }
        }

        o = $.extend(defaults, o);


        this.each(function() {
            var cthis = $(this);
            var cchildren = cthis.children(),
                cthisId = 'ap1';
            var currNr = -1;
            var i = 0;
            var ww, wh, tw, th, cw // -- controls width
                , ch // -- controls height
                , sw = 0 // -- scrubbar width
                ,
                sh, spos = 0 //== scrubbar prog pos
            ;
            var _audioplayerInner, _apControls = null,
                _apControlsLeft = null,
                _apControlsRight = null,
                _conControls, _conPlayPause, _controlsVolume, _scrubbar, _scrubbarbg_canvas, _scrubbarhover_canvas, _scrubbarprog_canvas, _theMedia
                , _cmedia = null
                , _cwatermark = null
                , _theThumbCon, _metaArtistCon, _scrubBgReflect = null,
                _scrubBgReflectCanvas = null,
                _scrubBgReflectCtx = null,
                _scrubProgReflect = null,
                _scrubProgCanvasReflect = null,
                _scrubProgCanvasReflectCtx = null,
                _scrubBgCanvas = null,
                _scrubBgCanvasCtx = null,
                _scrubProgCanvas = null,
                _scrubProgCanvasCtx = null,
                _commentsHolder = null,
                _commentsWriter = null,
                _currTime = null,
                _totalTime = null,
                _feed_fakePlayer = null,
                _feed_fakeButton = null;
            var busy = false,
                playing = false,
                muted = false,
                loaded = false,
                destroyed = false,
                google_analytics_sent_play_event = false,
                destroyed_for_rebuffer = false
                ,loop_active = false
            ;
            var time_total = 0
                ,time_curr = 0
                ,real_time_curr = 0 // -- we need these for sample..
                ,real_time_total = 0 // -- we need these for sample..
                ,sample_time_start = 0,
                sample_time_end = 0,
                sample_time_total = 0,
                sample_perc_start = 0,
                sample_perc_end = 0,
                attempt_reload = 0,
                currTime_outerWidth = 0;
            var index_extrahtml_toloads = 0;
            var last_vol = 1,
                last_vol_before_mute = 1,
                the_player_id = ''
                ,pcm_identifier = ''// -- can be either player id or source if player id is not set
            ;
            var inter_check, inter_checkReady, inter_audiobuffer_workaround_id = 0,
                inter_trigger_resize;
            var skin_minimal_canvasplay, skin_minimal_canvaspause;
            var is_flashplayer = false;
            var data_source = '',
                src_real_mp3 = '' // -- the real source of the mp3
                ,
                id_real_mp3 = '' // -- the real source of the mp3
                ,
                original_real_mp3 = '' // -- this is the original real mp3 for sainvg and identifying in the database
            ;

            var res_thumbh = false
                ,debug_var = false
                ,volume_dragging = false
                ,volume_set_by_user = false // -- this shows if the user actioned on the volume
                ,pcm_is_real = false
                ,pcm_try_to_generate = true


            ; // resize thumb height


            var skin_minimal_button_size = parseInt(o.skin_minimal_button_size, 10);

            // -- touch controls
            var scrubbar_moving = false
                ,scrubbar_moving_x = 0
                ,aux3 = 0
            ;

            var str_ie8 = '';

            //===spectrum stuff

            var javascriptNode = null;
            var audioCtx = null;
            var audioBuffer = null;
            var sourceNode = null;
            var analyser = null;
            var lastarray = []
                ,last_lastarray = null
            ;
            var webaudiosource = null;
            var canw = 100;
            var canh = 100;
            var barh = 100,
                scrubbar_h = 75
                ,design_thumbh
            ;
            var type = '';

            var sposarg = 0; // the % at which the comment will be placed

            var arr_the_comments = [];

            var str_audio_element = '';

            var lasttime_inseconds = 0;

            var controls_left_pos = 0;
            var controls_right_pos = 0;

            var ajax_view_submitted = 'auto',
                increment_views = 0
                ,type_for_fake_feed = 'audio'
            ;

            var starrating_index = 0,
                starrating_nrrates = 0,
                starrating_alreadyrated = -1;

            var playfrom = 'off',
                playfrom_ready = false;

            var waveform_peaks = []; // -- an array of peaks for the canvas waveform

            var defaultVolume = 1;

            var currIp = '127.0.0.1';
            var action_audio_end = null,
                action_audio_play = null,
                action_audio_play2 = null,
                action_audio_comment = null; // -- set a outer ended function ( for example for tracking your analytics


            var sw_suspend_enter_frame = false;

            var animation_flip = true,
                animation_pause = "M11,10 L18,13.74 18,22.28 11,26 M18,13.74 L26,18 26,18 18,22.28",
                animation_play = "M11,10 L17,10 17,26 11,26 M20,10 L26,10 26,26 20,26"
            ;


            var duration_viy = 20;

            var target_viy = 0;

            var begin_viy = 0;

            var finish_viy = 0;

            var change_viy = 0;

            var ggradient = null;


            if (isNaN(parseInt(o.design_thumbh, 10)) == false) {
                o.design_thumbh = parseInt(o.design_thumbh, 10);

            }
            if (String(o.design_thumbw).indexOf('%') == -1) {
                o.design_thumbw = parseInt(o.design_thumbw, 10);

            }
            // console.info(cthis, o);


            window.dzsap_player_index++;

            if (Number(o.sample_time_start) > 0) {
                sample_time_start = Number(o.sample_time_start);
                if (Number(o.sample_time_end) > 0) {
                    sample_time_end = Number(o.sample_time_end);

                    if (Number(o.sample_time_total) > 0) {
                        sample_time_total = Number(o.sample_time_total);


                        sample_perc_start = sample_time_start / sample_time_total;
                        sample_perc_end = sample_time_end / sample_time_total;

                    }
                }

            }

            if(o.autoplay=='on' && o.cue=='on'){
                o.preload_method = 'auto';
            }


            if(o.skinwave_preloader_code=='default'){
                // o.skinwave_preloader_code = '<svg class="loading-svg" width="21px" height="21px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring-alt"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#e3e3e3" fill="none" stroke-width="10" stroke-linecap="round"></circle><circle cx="50" cy="50" r="40" stroke="#b8926f" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg>';

                // <!-- By Sam Herbert (@sherb), for everyone. More @ http://goo.gl/7AJzbL -->
                o.skinwave_preloader_code = ' <svg class="loading-svg" width="30" height="30" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#fff"> <g fill="none" fill-rule="evenodd" stroke-width="2"> <circle cx="22" cy="22" r="1"> <animate attributeName="r" begin="0s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /> <animate attributeName="stroke-opacity" begin="0s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite" /> </circle> <circle cx="22" cy="22" r="1"> <animate attributeName="r" begin="-0.9s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /> <animate attributeName="stroke-opacity" begin="-0.9s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite" /> </circle> </g> </svg>';

            }


            //console.info(sample_perc_start,sample_perc_end);

            o.settings_trigger_resize = parseInt(o.settings_trigger_resize, 10);
            o.watermark_volume = parseFloat(o.watermark_volume);

            if (cthis.children('.extra-html').length > 0 && o.settings_extrahtml == '') {
                o.settings_extrahtml = cthis.children('.extra-html').eq(0).html();



                var re_ratesubmitted = /{\{ratesubmitted=(.?)}}/g;
                if (re_ratesubmitted.test(String(o.settings_extrahtml))) {
                    re_ratesubmitted.lastIndex = 0;
                    var auxa = (re_ratesubmitted.exec(String(o.settings_extrahtml)));


                    starrating_alreadyrated = auxa[1];

                    o.settings_extrahtml = String(o.settings_extrahtml).replace(re_ratesubmitted, '');

                    if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_player_rateSubmitted != undefined) {
                        $(o.parentgallery).get(0).api_player_rateSubmitted();
                    }
                }


                cthis.children('.extra-html').remove();
            }

            if (cthis.children('.extra-html-in-controls-right').length > 0 && o.settings_extrahtml_in_float_right == '') {
                o.settings_extrahtml_in_float_right = cthis.children('.extra-html-in-controls-right').eq(0).html();


                o.settings_extrahtml_in_float_right = String(o.settings_extrahtml_in_float_right).replace('{{svg_share_icon}}', svg_share_icon);
            }

            if (cthis.children('.extra-html-in-controls-left').length > 0 && o.settings_extrahtml_in_float_left == '') {
                o.settings_extrahtml_in_float_left = cthis.children('.extra-html-in-controls-left').eq(0).html();


            }




            init();

            function init() {
                //console.log(typeof(o.parentgallery)=='undefined');

                if (o.design_skin == '') {
                    o.design_skin = 'skin-default';
                }
                if (cthis.attr('class').indexOf("skin-") == -1) {
                    cthis.addClass(o.design_skin);
                }
                if (cthis.hasClass('skin-default')) {
                    o.design_skin = 'skin-default';
                }
                if (cthis.hasClass('skin-wave')) {
                    o.design_skin = 'skin-wave';
                }
                if (cthis.hasClass('skin-justthumbandbutton')) {
                    o.design_skin = 'skin-justthumbandbutton';
                }
                if (cthis.hasClass('skin-pro')) {
                    o.design_skin = 'skin-pro';
                }
                if (cthis.hasClass('skin-aria')) {
                    o.design_skin = 'skin-aria';
                }
                if (cthis.hasClass('skin-silver')) {
                    o.design_skin = 'skin-silver';
                }
                if (cthis.hasClass('skin-redlights')) {
                    o.design_skin = 'skin-redlights';
                }
                if (cthis.hasClass('skin-steel')) {
                    o.design_skin = 'skin-steel';
                }
                if (cthis.hasClass('skin-customcontrols')) {
                    o.design_skin = 'skin-customcontrols';
                }

                if(cthis.hasClass('skin-wave-mode-small')){
                    o.skinwave_mode = 'small'
                }
                if(cthis.hasClass('skin-wave-mode-alternate')){
                    o.skinwave_mode = 'alternate'
                }

                //console.info(o.design_skin, o.disable_volume);

                if (cthis.attr('data-viewsubmitted') == 'on') {
                    ajax_view_submitted = 'on';

                    // console.info('ajax_view_submitted from data-viewsubmitted', cthis);
                }
                if (cthis.attr('data-userstarrating')) {
                    starrating_alreadyrated = Number(cthis.attr('data-userstarrating'));
                }
                //                console.info(starrating_alreadyrated);

                if (cthis.hasClass('skin-minimal')) {
                    o.design_skin = 'skin-minimal';
                    if (o.disable_volume == 'default') {
                        o.disable_volume = 'on';
                    }

                    if (o.disable_scrub == 'default') {
                        o.disable_scrub = 'on';
                    }
                    o.disable_timer = 'on';
                }
                if (cthis.hasClass('skin-minion')) {
                    o.design_skin = 'skin-minion';
                    if (o.disable_volume == 'default') {
                        o.disable_volume = 'on';
                    }

                    if (o.disable_scrub == 'default') {
                        o.disable_scrub = 'on';
                    }

                    o.disable_timer = 'on';
                }

                if (o.design_skin == 'skin-default') {
                    if (o.design_thumbh == 'default') {
                        design_thumbh = cthis.height() - 40;
                        res_thumbh = true;
                    }
                }
                if (o.design_skin == 'skin-wave') {
                    cthis.addClass('skin-wave-mode-' + o.skinwave_mode);


                    if (o.skinwave_mode == 'small') {
                        if (o.design_thumbh == 'default') {
                            design_thumbh = 80;
                        }
                    }
                    cthis.addClass('skin-wave-wave-mode-' + o.skinwave_wave_mode);

                    if(o.skinwave_enableSpectrum=='on'){

                        cthis.addClass('skin-wave-is-spectrum');
                    }
                    cthis.addClass('skin-wave-wave-mode-canvas-mode-' + o.skinwave_wave_mode_canvas_mode);

                }

                if(o.design_color_bg){
                    o.design_wave_color_bg = o.design_color_bg;
                }
                if(o.design_color_highlight){
                    o.design_wave_color_progress = o.design_color_highlight;
                }

                // console.info(o.design_wave_color_bg, o.design_wave_color_prog);

                if (o.design_skin == 'skin-justthumbandbutton') {
                    if (o.design_thumbh == 'default') {
                        o.design_thumbh = '';
                        //                        res_thumbh = true;
                    }
                    o.disable_timer = 'on';
                    o.disable_volume = 'on';

                    if (o.design_animateplaypause == 'default') {
                        o.design_animateplaypause = 'on';
                    }
                }
                if (o.design_skin == 'skin-redlights') {
                    o.disable_timer = 'on';
                    o.disable_volume = 'off';
                    if (o.design_animateplaypause == 'default') {
                        o.design_animateplaypause = 'on';
                    }

                }
                if (o.design_skin == 'skin-steel') {
                    if (o.disable_timer == 'default') {

                        o.disable_timer = 'off';
                    }
                    o.disable_volume = 'on';
                    if (o.design_animateplaypause == 'default') {
                        o.design_animateplaypause = 'on';
                    }


                    if (o.disable_scrub == 'default') {
                        o.disable_scrub = 'on';
                    }

                }
                if (o.design_skin == 'skin-customcontrols') {
                    if (o.disable_timer == 'default') {

                        o.disable_timer = 'on';
                    }
                    o.disable_volume = 'on';
                    if (o.design_animateplaypause == 'default') {
                        o.design_animateplaypause = 'on';
                    }


                    if (o.disable_scrub == 'default') {
                        o.disable_scrub = 'on';
                    }

                }

                if (o.skinwave_wave_mode == 'canvas') {


                    o.skinwave_enableReflect = 'off';


                    cthis.addClass('skin-wave-no-reflect');
                }

                if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                    o.skinwave_wave_mode_canvas_reflection_size=0.5;
                    o.skinwave_wave_mode_canvas_waves_number=1;
                    o.skinwave_wave_mode_canvas_waves_padding=0;
                }

                if (o.skinwave_enableReflect == 'off') {

                    cthis.addClass('skin-wave-no-reflect');
                }

                if (o.design_thumbh == 'default') {
                    design_thumbh = 200;
                }
                if (o.embed_code == '') {
                    if (cthis.find('div.feed-embed-code').length > 0) {
                        o.embed_code = cthis.find('div.feed-embed-code').eq(0).html();
                    }
                }

                if (o.design_animateplaypause == 'default') {
                    o.design_animateplaypause = 'off';
                }

                if (o.design_animateplaypause == 'on') {
                    cthis.addClass('design-animateplaypause');
                }
                //                console.info(the_player_id, o.skinwave_comments_enable, o.skinwave_comments_playerid);

                if (o.skinwave_comments_playerid == '') {
                    if (typeof(cthis.attr('id')) != 'undefined') {
                        the_player_id = cthis.attr('id');
                    }
                    if (cthis.attr('data-playerid')) {
                        the_player_id = cthis.attr('data-playerid');
                    }
                } else {
                    the_player_id = o.skinwave_comments_playerid;

                    if (!(cthis.attr('id')) ) {
                        cthis.attr('id', the_player_id);
                    }
                }


                if(cthis.attr('data-playerid')){

                }else{
                    // console.info('the_player_id - ',the_player_id);
                    if (the_player_id == '') {
                        the_player_id = clean_string(cthis.attr('data-source'));
                        cthis.attr('data-playerid',the_player_id);
                    }
                }



                if (the_player_id == '' || isNaN(Number(the_player_id))) {
                    o.skinwave_comments_enable = 'off';

                }


                if (cthis.attr('data-fakeplayer')) {

                    // && (is_android() || is_ios()) == false
                    if (cthis.attr('data-type')) {

                        o.fakeplayer = $(cthis.attr('data-fakeplayer')).eq(0);
                        type_for_fake_feed = cthis.attr('data-type');
                        cthis.attr('data-type', 'fake');
                        o.type = 'fake';
                        type = 'fake';

                    }
                }

                if (o.construct_player_list_for_sync == 'on') {

                    if (dzsap_list_for_sync_sw_build == false) {

                        dzsap_list_for_sync_players = [];

                        dzsap_list_for_sync_sw_build = true;

                        $('.audioplayer.is-single-player,.audioplayer-tobe.is-single-player').each(function() {
                            var _t23 = $(this);

                            // console.info(_t23);
                            dzsap_list_for_sync_players.push(_t23);
                        })

                        // console.info(dzsap_list_for_sync_players);

                        clearTimeout(dzsap_list_for_sync_inter_build);

                        dzsap_list_for_sync_inter_build = setTimeout(function() {
                            dzsap_list_for_sync_sw_build = false;
                        }, 500);

                    }
                }


                playfrom = o.playfrom;

                if (isValid(cthis.attr('data-playfrom'))) {
                    playfrom = cthis.attr('data-playfrom');
                }

                if (isNaN(parseInt(playfrom, 10)) == false) {
                    playfrom = parseInt(playfrom, 10);
                }



                pcm_identifier = the_player_id; // -- the pcm identifier to send via ajax

                // console.warn('pcm identified', cthis, pcm_identifier);


                var _feed_obj = null;

                if (_feed_fakeButton) {

                    _feed_obj = _feed_fakeButton;
                } else {
                    if (_feed_fakePlayer) {

                        _feed_obj = _feed_fakePlayer;
                    } else {
                        _feed_obj = null;
                    }
                }



                if (_feed_obj) {

                    if (_feed_obj.attr('data-playerid')) {

                        pcm_identifier = _feed_obj.attr('data-playerid');
                    } else {

                        if (_feed_obj.attr('data-source')) {

                            pcm_identifier = _feed_obj.attr('data-source');
                        }
                    }
                }

                // console.info('inited - ', the_player_id, ' skinwave_comments_enable - ', o.skinwave_comments_enable, cthis);

                if (cthis.attr('data-type') == 'youtube') {
                    o.type = 'youtube';

                    type = 'youtube';
                }
                if (cthis.attr('data-type') == 'soundcloud') {
                    o.type = 'soundcloud';
                    type = 'soundcloud';
                }
                if (cthis.attr('data-type') == 'mediafile') {
                    o.type = 'audio';
                    type = 'audio';
                }
                if (cthis.attr('data-type') == 'shoutcast') {
                    o.type = 'shoutcast';
                    type = 'audio';
                    o.disable_timer = 'on';
                    //===might still use it for skin-wave

                    if (o.design_skin == 'skin-default') {
                        o.disable_scrub = 'on';
                    }
                    //                    o.disable_scrub = 'on';
                }

                if (type == '') {
                    type = 'audio';
                }

                src_real_mp3 = cthis.attr('data-source');
                if (type == 'audio') {
                    src_real_mp3 = cthis.attr('data-source');
                }

                //====we disable the function if audioplayer inited
                if (cthis.hasClass('audioplayer')) {
                    return;
                }
                //console.info('ceva');

                if (cthis.attr('id') != undefined) {
                    cthisId = cthis.attr('id');
                } else {
                    cthisId = 'ap' + dzsap_globalidind++;
                }

                if (is_ie8()) {
                    if (o.cue == 'off') {
                        o.cue = 'on';
                    }
                }

                cthis.children('.dzsap-wrapper-but').each(function(){
                    var aux = $(this).html();

                    aux = aux.replace('{{heart_svg}}',svg_heart_icon);
                    aux = aux.replace('{{svg_share_icon}}',svg_share_icon);

                    $(this).html(aux);
                }).wrapAll('<div class="dzsap-wrapper-buts"></div>');


                cthis.removeClass('audioplayer-tobe');
                cthis.addClass('audioplayer');


                if (cthis.find('.the-comments').length > 0 && cthis.find('.the-comments').eq(0).children().length > 0) {
                    arr_the_comments = cthis.find('.the-comments').eq(0).children();
                } else {
                    if (o.skinwave_comments_retrievefromajax == 'on') {

                        var data = {
                            action: 'dzsap_get_comments',
                            postdata: '1',
                            playerid: the_player_id
                        };





                        if (o.settings_php_handler) {
                            $.ajax({
                                type: "POST",
                                url: o.settings_php_handler,
                                data: data,
                                success: function(response) {
                                    //if(typeof window.console != "undefined" ){ console.log('Ajax - get - comments - ' + response); }

                                    cthis.prependOnce('<div class="the-comments"></div>', '.the-comments');

                                    if (response.indexOf('a-comment') > -1) {

                                        response = response.replace(/a-comment/g, 'a-comment dzstooltip-con');
                                        response = response.replace(/dzstooltip arrow-bottom/g, 'dzstooltip arrow-from-start transition-slidein arrow-bottom');

                                    }
                                    cthis.find('.the-comments').eq(0).html(response);

                                    arr_the_comments = cthis.find('.the-comments').eq(0).children();

                                    setup_controls_commentsHolder();

                                },
                                error: function(arg) {
                                    if (typeof window.console != "undefined") {
                                        console.log('Got this from the server: ' + arg, arg);
                                    };
                                }
                            });
                        }

                    }
                }

                if (o.skinwave_wave_mode == 'canvas') {
                    wave_mode_canvas_try_to_get_pcm();
                }




                //===ios does not support volume controls so just let it die
                //====== .. or autoplay FORCE STAFF


                if (is_ios() || is_android()) {

                    o.autoplay = 'off';
                    o.disable_volume = 'on';


                    if (o.cue == 'off') {
                        o.cue = 'on';
                    }
                    o.cue = 'on';
                }

                if (type == 'youtube') {
                    if (dzsap_ytapiloaded == false) {
                        var tag = document.createElement('script');

                        tag.src = "https://www.youtube.com/iframe_api";
                        var firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                        dzsap_ytapiloaded = true;
                    }
                }
                data_source = cthis.attr('data-source');



                //====sound cloud INTEGRATION //
                if (cthis.attr('data-source') != undefined && String(cthis.attr('data-source')).indexOf('https://soundcloud.com/') > -1) {
                    type = 'soundcloud';
                }
                //console.info(o.type);
                if (type == 'soundcloud') {

                    if (o.soundcloud_apikey == '') {
                        alert('soundcloud api key not defined, read docs!');
                    }
                    var aux = 'http://api.' + 'soundcloud.com' + '/resolve?url=' + data_source + '&format=json&consumer_key=' + o.soundcloud_apikey;
                    //console.info(aux);

                    if ((o.design_skin == 'skin-wave' && !cthis.attr('data-scrubbg')) || is_ie8()) {
                        o.skinwave_enableReflect = 'off';
                    }

                    aux = encodeURIComponent(aux);


                    var soundcloud_retriever = o.php_retriever + '?scurl=' + aux;
                    $.ajax({
                        type: "GET",
                        url: soundcloud_retriever
                        ,data: {}
                        ,async: true
                        ,dataType: 'text'
                        ,error: function (err, q,t) {

                            console.warn(err, q, t);
                        }
                        ,success: function (response) {

                            // console.log('got response - ', response);
                            var data = [];


                            try {
                                var data = JSON.parse(response);
                                // console.log('got json - ', data);
                                type = 'audio';


                                if (data == '') {
                                    cthis.addClass('errored-out');
                                    cthis.append('<div class="feedback-text">soundcloud track does not seem to serve via api</div>');
                                }



                                console.info('o.design_skin', o.design_skin);
                                original_real_mp3 = cthis.attr('data-source');
                                if(data.stream_url){

                                    cthis.attr('data-source', data.stream_url + '?consumer_key=' + o.soundcloud_apikey + '&origin=localhost');
                                }else{

                                    cthis.addClass('errored-out');
                                    cthis.append('<div class="feedback-text ">this soundcloud track does not allow streaming  </div>');
                                }
                                src_real_mp3 = cthis.attr('data-source');


                                if (cthis.attr('data-pcm')) {


                                    pcm_is_real = true;
                                }
                                if (o.design_skin == 'skin-wave'){
                                    if (o.design_skin == 'skin-wave' && cthis.attr('data-scrubbg') == undefined) {


                                        if (o.skinwave_wave_mode != 'canvas') {

                                            cthis.attr('data-scrubbg', data.waveform_url);
                                            cthis.attr('data-scrubprog', data.waveform_url);
                                            _scrubbar.find('.scrub-bg').eq(0).append('<div class="scrub-bg-div"></div>');
                                            _scrubbar.find('.scrub-bg').eq(0).append('<img src="' + cthis.attr('data-scrubbg') + '" class="scrub-bg-img"/>');
                                            _scrubbar.children('.scrub-prog').eq(0).append('<div class="scrub-prog-div"></div>');

                                            _scrubbar.find('.scrub-bg').css({
                                                //'height' : '100%'
                                                'top': 0
                                            })
                                        }

                                    }
                                    if (o.skinwave_wave_mode == 'canvas') {
                                        if (pcm_is_real == false) {

                                            init_generate_wave_data({
                                                'call_from': 'init(), pcm not real..'
                                            });
                                        }
                                    }
                                }


                                //                        if(window.console) { console.info(data); };

                                if (o.cue == 'on') {
                                    setup_media();
                                }
                            }catch(err){
                                console.log('soduncloud parse error -'  ,response, ' - ',soundcloud_retriever);
                            }
                        }
                    });

                    //                    type='audio';
                }
                //====END soundcloud INTEGRATION//


                if ((can_play_mp3() == false && cthis.attr('data-sourceogg') ) || is_ie8() || o.settings_useflashplayer == 'on') {
                    is_flashplayer = true;
                }

                setup_structure(); //  -- inside init()

                //console.info(cthis, is_ios(), o.type);
                //trying to access the youtube api with ios did not work

                //console.log(is_flashplayer)


                if (o.scrubbar_tweak_overflow_hidden == 'on') {

                    cthis.addClass('scrubbar-tweak-overflow-hidden-on');
                } else {

                    cthis.removeClass('scrubbar-tweak-overflow-hidden-on');
                }





                //                console.info(o.design_skin, type, o.skinwave_comments_enable, o.design_skin=='skin-wave' && (type=='audio'||type=='soundcloud') && o.skinwave_comments_enable=='on');

                //console.log(o.design_skin, type, o.skinwave_comments_enable)

                if (o.design_skin == 'skin-wave' && (type == 'audio' || type == 'soundcloud' || type == 'fake') && o.skinwave_comments_enable == 'on' && false) {

                    var aux5 = '<div class="comments-holder">';



                    if (o.skinwave_comments_links_to) {

                        aux5 += '<a href="' + o.skinwave_comments_links_to + '" target="_blank" class="the-bg"></a>';
                    } else {

                        aux5 += '<div class="the-bg"></div>';
                    }


                    aux5 += '</div><div class="clear"></div><div class="comments-writer"><div class="comments-writer-inner"><div class="setting"><div class="setting-label"></div><textarea name="comment-text" placeholder="Your comment.." type="text" class="comment-input"></textarea><div class="float-right"><button class="submit-ap-comment dzs-button float-right">Submit</button><button class="cancel-ap-comment dzs-button float-right">Cancel</button></div><div class="overflow-it"><input placeholder="Your email.." name="comment-email" type="text" class="comment-input"/></div><div class="clear"></div></div></div></div>';

                    cthis.appendOnce(aux5);
                    _commentsHolder = cthis.find('.comments-holder').eq(0);
                    _commentsWriter = cthis.find('.comments-writer').eq(0);



                    setup_controls_commentsHolder();
                    _commentsHolder.find('.the-bg').bind('click', click_comments_bg);
                    _commentsWriter.find('.cancel-ap-comment').bind('click', click_cancel_comment);
                    _commentsWriter.find('.submit-ap-comment').bind('click', click_submit_comment);
                }


                if (o.settings_extrahtml != '' && false) {

                    cthis.append('<div class="extra-html">' + o.settings_extrahtml + '</div>');

                }

                if (type == 'youtube') {
                    if (dzsap_list) {
                        dzsap_list.push(cthis);
                    }

                    _theMedia.append('<div id="ytplayer_' + cthisId + '"></div>');
                    cthis.get(0).fn_yt_ready = check_yt_ready;

                    if (window.YT) {
                        check_yt_ready();
                    }
                }




                //console.info();






                if (type == 'audio') {


                    //                    img = document.createElement('img');
                    //                    img.onerror = function(){
                    //                        return;
                    //                        if(cthis.children('.meta-artist').length>0){
                    //                            _audioplayerInner.children('.meta-artist').html('audio not found...');
                    //                        }else{
                    //                            _audioplayerInner.append('<div class="meta-artist">audio not found...</div>');
                    //                            _audioplayerInner.children('.meta-artist').eq(0).wrap('<div class="meta-artist-con"></div>');
                    //                        }
                    //                    };
                    //                    img.src= cthis.attr('data-source');

                }

                if (o.autoplay == 'on' && o.cue == 'on') {
                    increment_views = 1;
                }


                if (type == 'youtube' && is_ios()) {
                    if (cthis.height() < 200) {
                        cthis.height(200);
                    }
                    aux = '<iframe width="100%" height="100%" src="//www.youtube.com/embed/' + data_source + '" frameborder="0" allowfullscreen></iframe>';
                    cthis.html(aux);
                    return;
                } else {
                    //soundcloud will setupmedia when api done

                    // console.info(o.cue, type);
                    if (o.cue == 'on' && type != 'soundcloud') {


                        if (is_android() || is_ios()) {

                            cthis.find('.playbtn').bind('click', play_media);
                        }
                        setup_media();


                    } else {

                        cthis.find('.playbtn').bind('click', click_for_setup_media);
                        cthis.find('.scrubbar').bind('click', click_for_setup_media);
                        handleResize();
                    }

                }

                setInterval(function() {
                    debug_var = true;
                }, 3000);

                // -- we call the api functions here
                //console.info('api sets');

                cthis.get(0).api_destroy = destroy_it; // -- destroy the player and the listeners
                cthis.get(0).api_play = play_media; // -- play the media
                cthis.get(0).api_get_last_vol = get_last_vol; // -- play the media
                cthis.get(0).api_click_for_setup_media = click_for_setup_media; // -- play the media
                cthis.get(0).api_handleResize = handleResize; // -- force resize event
                cthis.get(0).api_set_playback_speed = set_playback_speed; // -- set the playback speed, only works for local hosted mp3
                cthis.get(0).api_change_media = change_media; // -- change the media file from the API
                cthis.get(0).api_seek_to_perc = seek_to_perc; // -- seek to percentage ( for example seek to 0.5 skips to half of the song )
                cthis.get(0).api_seek_to = seek_to; // -- seek to percentage ( for example seek to 0.5 skips to half of the song )
                cthis.get(0).api_seek_to_onlyvisual = seek_to_onlyvisual; // -- seek to perchange but only visually ( does not actually skip to that ) , good for a fake player
                cthis.get(0).api_set_volume = set_volume; // -- set a volume
                cthis.get(0).api_visual_set_volume = visual_set_volume; // -- set a volume
                cthis.get(0).api_destroy_listeners = destroy_listeners; // -- set a volume

                cthis.get(0).api_pause_media = pause_media; // -- pause the media
                cthis.get(0).api_pause_media_visual = pause_media_visual; // -- pause the media, but only visually
                cthis.get(0).api_play_media = play_media; // -- play the media
                cthis.get(0).api_play_media_visual = play_media_visual; // -- play the media, but only visually
                cthis.get(0).api_change_visual_target = change_visual_target; // -- play the media, but only visually
                cthis.get(0).api_change_design_color_highlight = change_design_color_highlight; // -- play the media, but only visually

                cthis.get(0).api_get_time_curr = function() {
                    return time_curr;
                };
                cthis.get(0).api_set_time_curr = function(arg) {
                    time_curr = arg;
                };
                cthis.get(0).api_get_time_total = function() {
                    return time_total;
                };
                cthis.get(0).api_set_time_total = function(arg) {
                    time_total = arg;
                };

                cthis.get(0).api_set_action_audio_play = function(arg) {
                    action_audio_play = arg;
                };
                cthis.get(0).api_set_action_audio_end = function(arg) {
                    action_audio_end = arg;
                };
                cthis.get(0).api_set_action_audio_comment = function(arg) {
                    action_audio_comment = arg;
                };
                cthis.get(0).api_try_to_submit_view = try_to_submit_view;

                //console.log(cthis.get(0));

                //console.info(o);
                if (o.action_audio_play) {
                    action_audio_play = o.action_audio_play;
                };
                if (o.action_audio_play2) {
                    action_audio_play2 = o.action_audio_play2;
                };

                if (o.action_audio_end) {
                    action_audio_end = o.action_audio_end;
                }



                //console.log(o.design_skin);
                if (o.design_skin == 'skin-minimal') {
                    check_time({
                        'fire_only_once': true
                    });
                }


                cthis.on('click','.dzsap-repeat-button,.dzsap-loop-button',handle_mouse);
                // cthis.on('mouseover',handle_mouse);
                cthis.on('mouseenter',handle_mouse);
                cthis.on('mouseleave',handle_mouse);



                $(window).bind('resize', handleResize);
                handleResize();



                _scrubbar.on('touchstart', function(e) {
                    if(playing){

                        scrubbar_moving = true;
                    }
                })
                $(document).on('touchmove', function(e) {
                    if (scrubbar_moving) {
                        scrubbar_moving_x = e.originalEvent.touches[0].pageX;


                        aux3 = scrubbar_moving_x - _scrubbar.offset().left;

                        if (aux3 < 0) {
                            aux3 = 0;
                        }
                        if (aux3 > _scrubbar.width()) {
                            aux3 = _scrubbar.width();
                        }

                        seek_to_perc(aux3 / _scrubbar.width());


                        //console.info(aux3);


                    }
                })

                $(document).on('touchend', function(e) {
                    scrubbar_moving = false;
                })


                // console.info("hmm",cthis);
                cthis.off('click','.btn-like');
                cthis.on('click','.btn-like',  click_like);


                $(document).delegate('.star-rating-con', 'mousemove', mouse_starrating);
                $(document).delegate('.star-rating-con', 'mouseleave', mouse_starrating);
                $(document).delegate('.star-rating-con', 'click', mouse_starrating);

                setTimeout(function(){

                    handleResize();


                    if(o.skinwave_wave_mode=='canvas'){

                        calculate_dims_time();

                        setTimeout(function(){
                            calculate_dims_time();


                        },100)
                    }

                },100)


                cthis.find('.btn-menu-state').eq(0).bind('click', click_menu_state);


                cthis.on('click', '.prev-btn,.next-btn',handle_mouse);
            }

            function calculate_dims_time(){
                var reflection_size = parseFloat(o.skinwave_wave_mode_canvas_reflection_size);

                if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                    reflection_size = 0;
                    o.skinwave_timer_static='on';
                }

                reflection_size = 1-reflection_size;


                if(o.design_skin=='skin-wave'){
                    if(_commentsHolder){

                        if(reflection_size==0){

                            _commentsHolder.css('top',_scrubbar.offset().top - cthis.offset().top + _scrubbar.height()*reflection_size - _commentsHolder.height());
                        }else{

                            // console.info(_scrubbar.height()*reflection_size);


                            // console.info(_scrubbar.offset().top, cthis.offset().top, _scrubbar.offset().top - cthis.offset().top, reflection_size, _scrubbar.height(), _currTime.outerHeight());

                            _commentsHolder.css('top', _scrubbar.offset().top - cthis.offset().top + _scrubbar.height()*reflection_size);
                        }
                    }


                    _currTime.css('top', _scrubbar.height()*reflection_size- _currTime.outerHeight());
                    _totalTime.css('top', _scrubbar.height()*reflection_size- _totalTime.outerHeight());
                }

                console.info('reflection_size - ',reflection_size);

                cthis.attr('data-reflection-size',reflection_size);
            }

            function select_all(el) {
                if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
                    var range = document.createRange();
                    range.selectNodeContents(el);
                    var sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                } else if (typeof document.selection != "undefined" && typeof document.body.createTextRange != "undefined") {
                    var textRange = document.body.createTextRange();
                    textRange.moveToElementText(el);
                    textRange.select();
                }
            }

            function change_visual_target(arg, pargs) {
                // -- change the visual target, the main is the main palyer playing and the visual target is the player which is a visual representation of this

                //console.info(arg);

                var margs = {

                }


                // return false;


                if (pargs) {
                    margs = $.extend(margs, pargs);
                }


                _feed_fakePlayer = arg;

                if(playing){
                    if(_feed_fakePlayer && _feed_fakePlayer.get(0) && _feed_fakePlayer.get(0).api_play_media_visual) {
                        _feed_fakePlayer.get(0).api_play_media_visual();
                    }
                }

            }

            function change_design_color_highlight(arg) {
                // -- change the visual target, the main is the main palyer playing and the visual target is the player which is a visual representation of this

                //console.info(arg);

                o.design_wave_color_progress = arg;

                if(o.skinwave_wave_mode=='canvas'){
                    draw_canvas(_scrubbarbg_canvas.get(0), cthis.attr('data-pcm'), "#" + o.design_wave_color_bg);
                    draw_canvas(_scrubbarprog_canvas.get(0), cthis.attr('data-pcm'), "#" + o.design_wave_color_progress);
                }

            }


            function change_media(arg, pargs) {
                // -- change media source for the player / change_media("song.mp3", {type:"audio", fakeplayer_is_feeder:"off"});


                var margs = {
                    type: '',
                    fakeplayer_is_feeder: 'off' // -- this is OFF in case there is a button feeding it, and on if it's an actiual player
                    ,call_from: 'default'
                    ,source: 'default'
                    ,pcm: ''
                    ,artist:""
                    ,song_name:""
                    ,thumb:""
                    ,thumb_link:""
                    ,autoplay:"on"
                    ,cue:"on"
                    ,feeder_type:"player"
                    ,watermark:""
                    ,watermark_volume:""
                }



                ajax_view_submitted = 'on'; // -- view submitted from caller

                var handle_resize_delay = 500;
                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                var _arg = arg;

                // console.info(_feed_fakePlayer,margs.fakeplayer_is_feeder);
                if (_feed_fakePlayer) {
                    _feed_fakePlayer.get(0).api_pause_media_visual();
                }



                // console.log('change_media', "margs - ", margs, cthis, _feed_fakePlayer, arg);
                if (margs.fakeplayer_is_feeder == 'on') {
                    _feed_fakePlayer = arg;

                    margs.source = _feed_fakePlayer.attr('data-source');

                    if(_feed_fakePlayer.attr('data-pcm')){
                        margs.pcm = _feed_fakePlayer.attr('data-pcm');
                    }


                    if (_feed_fakePlayer.find('.meta-artist').length > 0) {
                        margs.artist = _arg.find('.the-artist').eq(0).html();
                        margs.song_name = _arg.find('.the-name').eq(0).html();
                    }


                    if (_feed_fakePlayer.attr('data-thumb')) {
                        margs.thumb = _arg.attr('data-thumb');
                    }


                    if (_feed_fakePlayer.attr('data-thumb_link')) {

                        margs.thumb_link = _arg.attr('data-thumb_link');

                    }
                    if (_feed_fakePlayer.attr('data-scrubbg')) {

                        margs.scrubbg = _arg.attr('data-scrubbg');

                    }
                    if (_feed_fakePlayer.attr('data-scrubprog')) {

                        margs.scrubprog = _arg.attr('data-scrubprog');

                    }
                    if (_feed_fakePlayer.attr('data-soft-watermark')) {

                        margs.watermark = _arg.attr('data-soft-watermark');

                    }
                    if (_feed_fakePlayer.attr('data-watermark-volume')) {

                        margs.watermark_volume = _arg.attr('data-watermark-volume');

                    }

                    if(_feed_fakePlayer.attr('data-playerid')){
                        cthis.attr('data-feed-playerid', _feed_fakePlayer.attr('data-playerid'));
                    }else{

                        cthis.attr('data-feed-playerid', '');
                    }
                    _feed_fakeButton = null;

                } else {
                    _feed_fakePlayer = null;
                    _feed_fakeButton = arg;
                }




                console.info('change_media()');
                // --- if the media is the same DON'T CHANGE IT
                if (_feed_fakePlayer ) {

                    if(cthis.attr('data-source') == arg.attr('data-source')){

                        return false;
                    }

                }else{


                    if(cthis.attr('data-source') == arg){

                        return false;
                    }

                }

                if(_feed_fakeButton){

                    var _c = _feed_fakeButton;
                    margs.source = _c.attr('data-source');

                    if(_c.attr('data-pcm')){
                        margs.pcm = _c.attr('data-pcm');
                    }


                    if (_c.find('.meta-artist').length > 0) {
                        margs.artist = _arg.find('.the-artist').eq(0).html();
                        margs.song_name = _arg.find('.the-name').eq(0).html();

                        // console.warn('song_name', _arg.find('.the-name'), _arg.find('.the-name').eq(0).html())
                    }


                    if (_c.attr('data-thumb')) {
                        margs.thumb = _arg.attr('data-thumb');
                    }


                    if (_c.attr('data-thumb_link')) {

                        margs.thumb_link = _arg.attr('data-thumb_link');

                    }
                }


                console.info('change_media margs - ',margs);
                cthis.removeClass('meta-loaded');

                // console.info('change_media()',arg,margs, cthis);

                if (cthis.parent().hasClass('audioplayer-was-loaded')) {

                    cthis.parent().addClass('audioplayer-loaded');
                    cthis.parent().removeClass('audioplayer-was-loaded');
                }


                cthis.removeClass('errored-out');




                destroy_media();





                //console.info(cthis);


                cthis.attr('data-source', margs.source);
                cthis.attr('data-soft-watermark', margs.watermark);


                if(margs.watermark_volume){
                    o.watermark_volume = margs.watermark_volume;
                }else{

                    o.watermark_volume = 1;
                }


                console.info('o.watermark_volume - ',o.watermark_volume);


                if (margs.type=='mediafile') {
                    margs.type='audio';
                }

                if (margs.type) {

                    if (margs.type == 'soundcloud') {
                        margs.type = 'audio';
                    }
                    if (margs.type == 'album_part') {
                        margs.type = 'audio';
                    }
                    cthis.attr('data-type', margs.type);
                    type = margs.type;
                    o.type = margs.type;
                }

                loaded = false;
                // console.info('hmmdadada', margs, o.change_media_animate_skinwave_mode_small);

                if (o.design_skin == 'skin-wave' && o.skinwave_mode == 'small' && o.change_media_animate_skinwave_mode_small=='on') {
                    cthis.addClass('transitioning-change-media');



                    // -- artist
                    if (margs.artist || margs.song_name) {

                        var aux_l = 0;
                        if (_metaArtistCon && _metaArtistCon.offset()) {

                            aux_l = _metaArtistCon.offset().left - cthis.offset().left - 13;
                        }
                        //console.log(aux_l);
                        _metaArtistCon.css({

                            'position': 'absolute',
                            'top': '16px',
                            'left': aux_l + 'px'
                        });
                        _metaArtistCon.animate({

                            'top': '-50px',
                            'opacity': '0'
                        }, {
                            duration: 300
                        })


                        if (cthis.find('.the-thumb-con').length > 0) {
                            cthis.find('.the-thumb-con').addClass('transitioning-out');
                        } else {

                        }

                        _apControlsLeft.append('<div class="meta-artist-con transitioning" style="top:55px;"><div class="meta-artist"><div class="meta-artist"><span class="the-artist">'+margs.artist+'</span>&nbsp;<span class="the-name">'+margs.song_name+'</span></div></div></div>');

                        cthis.find('.meta-artist-con.transitioning').eq(0).animate({

                            'top': '18px'
                        }, {
                            duration: 300,
                            complete: function() {
                                //console.info(this);
                                $(this).css('top', '');
                                $(this).removeClass('transitioning');
                                _metaArtistCon = $(this);

                            }
                        })


                    }
                    // console.log(_arg, _arg.attr('data-thumb'));

                    if (margs.thumb) {
                        if (cthis.find('.the-thumb-con').length > 0) {
                            cthis.find('.the-thumb-con').addClass('transitioning-out');
                        } else {

                        }


                        var aux_mec = ';';


                        cthis.addClass('has-thumb');


                        cthis.find('.the-thumb-con.transitioning-out').css({

                            'position': 'absolute',
                            'top': '0px',
                            'left': 0 + 'px'
                        });

                        //console.log(_theThumbCon);
                        cthis.find('.the-thumb-con.transitioning-out').animate({

                            'top': '-80px'
                        }, {
                            duration: 500
                        })



                        var aux_thumb_con_str = '';
                        var str_thumbh = '';

                        if (margs.thumb_link) {
                            aux_thumb_con_str += '<a href="' + margs.thumb_link + '"';
                        } else {
                            aux_thumb_con_str += '<div';
                        }
                        aux_thumb_con_str += ' class="the-thumb-con" style="top: 80px;"><div class="the-thumb" style="' + str_thumbh + '  background-image:url(' + margs.thumb + ')"></div>';


                        if (margs.thumb_link) {
                            aux_thumb_con_str += '</a>';
                        } else {
                            aux_thumb_con_str += '</div>';
                        }

                        _apControlsLeft.prepend(aux_thumb_con_str);


                        cthis.find('.the-thumb-con').eq(0).animate({

                            'top': '0'
                        }, {
                            duration: 700
                        })

                    } else {

                        cthis.removeClass('has-thumb');
                    }








                    // -- still skin-wave and small
                    // -- change media
                    if (o.skinwave_wave_mode == 'canvas') {

                        // console.info("HMMM canvas")

                        src_real_mp3 = _arg.attr('data-source');

                        if (margs.pcm) {

                            generate_wave_data_animate(_arg.attr('data-pcm'));
                            cthis.attr('data-pcm', _arg.attr('data-pcm'));
                        } else {
                            // console.info("HMMM canvas")
                            init_generate_wave_data({
                                'call_from':'regenerate_canvas_from_change_media'
                            });
                        }
                    } else {
                        if (cthis.find('.scrub-bg').length > 0 && margs.scrubbg) {

                            var aux_mec = ';';


                            //console.log(_theThumbCon);
                            cthis.find('.scrub-bg,.scrub-prog,.scrub-bg-reflect,.scrub-prog-reflect').animate({

                                'opacity': 0
                            }, {
                                duration: 500
                            })

                            var aux_str_scrubbar = '';



                            aux_str_scrubbar += '<div class="scrub-bg is-new" style="opacity: 0;"></div><div class="scrub-buffer"></div><div class="scrub-prog is-new"  style="opacity: 0;"></div>';

                            if (o.design_skin == 'skin-wave' && o.skinwave_enableReflect == 'on') {
                                aux_str_scrubbar += '<div class="scrub-bg-reflect is-new" style="opacity: 0;"></div>';
                                aux_str_scrubbar += '<div class="scrub-prog-reflect is-new" style="opacity: 0;"></div>';

                            }

                            if (sample_perc_start) {

                                aux_str_scrubbar += '<div class="sample-block-start is-new" style="width: ' + (sample_perc_start * 100) + '%"></div>'
                            }
                            if (sample_perc_end) {

                                aux_str_scrubbar += '<div class="sample-block-end is-new" style="left: ' + (sample_perc_end * 100) + '%; width: ' + (100 - (sample_perc_end * 100)) + '%"></div>'
                            }

                            _scrubbar.children('.total-time').before(aux_str_scrubbar);



                            if (margs.scrubbg) {
                                _scrubbar.children('.scrub-bg.is-new').append('<img class="scrub-bg-img" src="' + margs.scrubbg + '"/>');
                            }
                            if (margs.scrubprog) {
                                _scrubbar.children('.scrub-prog.is-new').eq(0).append('<img class="scrub-prog-img" src="' + margs.scrubprog + '"/>');
                            }
                            _scrubbar.find('.scrub-bg-img').css({
                                // 'width' : _scrubbar.children('.scrub-bg.is-new').width()
                            });
                            _scrubbar.find('.scrub-prog-img').css({
                                'width': _scrubbar.children('.scrub-bg.is-new').width()
                            });
                            //console.info(o.skinwave_enableReflect);
                            if (o.skinwave_enableReflect == 'on') {
                                _scrubbar.children('.scrub-bg-reflect.is-new').eq(0).append('<img class="scrub-bg-img-reflect" src="' + margs.scrubbg + '"/>');
                                if (cthis.attr('data-scrubprog') != undefined) {
                                    _scrubbar.children('.scrub-prog-reflect.is-new').eq(0).append('<img class="scrub-prog-img-reflect" src="' + margs.scrubprog + '"/>');
                                }


                                _scrubbar.find('.scrub-bg-img').css({
                                    'transform-origin': '100% 100%'
                                })
                                _scrubbar.find('.scrub-prog-img').css({
                                    'transform-origin': '100% 100%'
                                })
                                _scrubbar.find('.scrub-prog-img-reflect').css({
                                    'width': _scrubbar.children('.scrub-bg.is-new').width()
                                });
                            }


                            setTimeout(function() {



                                //console.info(cthis.find('.scrub-bg.is-new,.scrub-prog.is-new,.scrub-bg-reflect.is-new,.scrub-prog-reflect.is-new'));

                                cthis.find('.scrub-bg.is-new,.scrub-prog.is-new').animate({

                                    'opacity': 1
                                }, {
                                    duration: 500,
                                    queue: false
                                })
                                cthis.find('.scrub-bg-reflect.is-new,.scrub-prog-reflect.is-new').animate({

                                    'opacity': 0.5
                                }, {
                                    duration: 500,
                                    queue: false
                                })
                            }, 100)

                        }

                    }



                    setTimeout(function() {
                        if (cthis.find('.meta-artist-con').length > 1) {
                            cthis.find('.meta-artist-con').eq(0).remove();
                            _metaArtistCon = cthis.find('.meta-artist-con').eq(0);

                        }
                        if (cthis.find('.the-thumb-con').length > 1) {
                            cthis.find('.the-thumb-con').eq(1).remove();
                            _theThumbCon = cthis.find('.the-thumb-con').eq(0);
                        }


                        if (o.skinwave_wave_mode == 'canvas') {

                        } else {

                            _scrubbar.find('.scrub-bg:not(.is-new)').remove();
                            _scrubbar.find('.scrub-prog:not(.is-new)').remove();
                        }



                        _scrubbar.find('.scrub-bg-reflect:not(.is-new)').remove();
                        _scrubbar.find('.scrub-prog-reflect:not(.is-new)').remove();
                        _scrubbar.find('.is-new').removeClass('is-new');


                        cthis.removeClass('transitioning-change-media');
                    }, 900);
                }else{
                    if(o.design_skin=='skin-wave'){
                        if (o.skinwave_wave_mode == 'canvas') {

                            if(_feed_fakePlayer){

                                src_real_mp3 = _arg.attr('data-source');

                            }else{
                                src_real_mp3 = arg;

                            }





                            // console.groupCollapsed('margs pcm');
                            // console.info('margs pcm - ',margs.pcm, margs.pcm!='');
                            // console.groupEnd();

                            if (margs.pcm!='') {

                                generate_wave_data_animate(margs.pcm);
                                cthis.attr('data-pcm', margs.pcm);
                            } else {



                                _scrubbar.addClass('fast-animate-scrubbar');

                                cthis.removeClass('scrubbar-loaded');
                                setTimeout(function(){
                                },10)
                                setTimeout(function(){
                                    cthis.removeClass('fast-animate-scrubbar');


                                    // console.info("HMMM canvas")
                                    pcm_is_real=false;
                                    // pcm_identifier = src_real_mp3; // -- let's reload this so it does have nothing to do with the id

                                    cthis.attr('data-pcm','');
                                    pcm_identifier = '';

                                    wave_mode_canvas_try_to_get_pcm();
                                    init_generate_wave_data({
                                        'call_from': 'regenerate_canvas_from_change_media'
                                    });

                                },120);


                            }


                        }



                        // console.info(' artist - ',margs.artist, cthis.find('.the-artist'), margs)
                        if(margs.artist){

                            cthis.find('.the-artist').html(margs.artist);

                        }
                        if(margs.song_name){

                            cthis.find('.the-name').html(margs.song_name);

                        }
                        if(margs.thumb){

                            if(cthis.find('.the-thumb').length){

                                cthis.find('.the-thumb').css('background-image', 'url('+margs.thumb+')');
                            }else{
                                cthis.attr('data-thumb', margs.thumb);
                                struct_generate_thumb();
                            }

                        }
                    }
                }




                if (o.design_skin == 'skin-silver') {





                    var aux_l = 0;



                    if (_metaArtistCon && _metaArtistCon.length > 0) {

                        aux_l = _metaArtistCon.offset().left - cthis.offset().left - 13;


                        //console.log(aux_l);
                        _metaArtistCon.css({

                            'position': 'absolute',
                            'top': '0px',
                            'left': aux_l + 'px'
                        });
                        _metaArtistCon.animate({

                            'top': '-50px',
                            'opacity': '0'
                        }, {
                            duration: 300
                        })

                    } else {
                        aux_l = 0;
                    }
                    if (_arg.find('.meta-artist').length > 0) {

                    }



                    // -- still skin-silver

                    var meta_artist_html = '';
                    var meta_artist_thumb = '';

                    if (_arg.find('.meta-artist').eq(0).html()) {
                        meta_artist_html = _arg.find('.meta-artist').eq(0).html();
                    }



                    if (_arg.attr('data-thumb')) {
                        cthis.addClass('has-thumb');
                        if (cthis.find('.the-thumb-con').length > 0) {
                            cthis.find('.the-thumb-con').addClass('transitioning-out');
                        } else {

                        }


                        meta_artist_thumb += '<div class="the-thumb-con" style=""><div class="the-thumb" style="  background-image:url(' + _arg.attr("data-thumb") + ')"></div></div>';


                        // if(_arg.attr('data-thumb_link')){
                        //     aux_thumb_con_str += '</a>';
                        // }else{
                        //     aux_thumb_con_str += '</div>';
                        // }




                    } else {

                        cthis.removeClass('has-thumb');
                    }





                    cthis.addClass('transitioning-change-media');
                    _apControlsRight.append('<div class="meta-artist-con transitioning" style="top:55px;">' + meta_artist_thumb + '<div class="meta-artist">' + meta_artist_html + '</div></div>');

                    if (aux_l == 0) {
                        aux_l = cthis.width() - _apControlsRight.find('.meta-artist-con.transitioning').last().width()
                    }

                    cthis.find('.meta-artist-con').last().css({
                        'top': '50px',
                        'position': 'relative'
                    })
                    cthis.find('.meta-artist-con').last().animate({

                        'top': '0px'
                    }, {
                        duration: 500
                    });

                    setTimeout(function() {
                        if (cthis.find('.meta-artist-con').length > 1) {
                            cthis.find('.meta-artist-con').eq(0).remove();
                            _metaArtistCon = cthis.find('.meta-artist-con').eq(0);
                            _metaArtistCon.css({
                                'position': 'relative',
                                'left': '0'
                            })

                        } else {

                            _metaArtistCon = cthis.find('.meta-artist-con').eq(0);
                            _metaArtistCon.css({
                                'position': 'relative',
                                'left': '0'
                            })
                        }


                        cthis.removeClass('transitioning-change-media');
                    }, 900);
                }

                handle_resize_delay = 100;
                if (_feed_fakePlayer && _arg.find('.meta-artist').eq(0).html()) {

                }


                setup_media({
                    'call_from': 'change_media'
                });

                if(last_vol){

                    set_volume(last_vol, {
                        call_from: "change_media"
                    });
                }


                if (type == 'fake') {
                    return false;

                }

                if(o.action_audio_change_media){
                    o.action_audio_change_media(arg,margs);
                }


                //console.info("IS MOBILE - ",is_mobile());
                if(margs.autoplay=='on' && is_mobile()==false){
                    play_media_visual();

                    setTimeout(function() {

                        play_media({
                            'call_from':'margs.autoplay'
                        });
                    }, 500);
                }
                setTimeout(function() {

                    handleResize();
                }, handle_resize_delay)
            }


            function setup_controls_commentsHolder() {


                for (i = 0; i < arr_the_comments.length; i++) {
                    if (_commentsHolder && arr_the_comments[i] != null) {
                        _commentsHolder.append(arr_the_comments[i]);

                    }
                }
            }

            function destroy_listeners() {


                if (destroyed) {
                    return false;
                }



                sw_suspend_enter_frame = true;

            }

            function destroy_it() {


                if (destroyed) {
                    return false;
                }

                if (playing) {
                    pause_media();
                }

                cthis.remove();
                cthis = null;

                destroyed = true;
            }

            function click_for_setup_media(e, pargs) {
                // console.info('click_for_setup_media', cthis, pargs);

                //console.info(e.target);
                //cthis.unbind('click', click_for_setup_media);



                var margs = {

                    'do_not_autoplay': false
                };

                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                cthis.find('.playbtn').unbind('click', click_for_setup_media);
                cthis.find('.scrubbar').unbind('click', click_for_setup_media);

                setup_media(margs);


                if (is_android() || is_ios()) {

                    play_media({
                        'call_from':'click_for_setup_media'
                    });
                }
            }

            function blur_ap() {
                //console.log('ceva');
                hide_comments_writer();
            }

            function click_menu_state(e) {

                if (o.parentgallery && typeof(o.parentgallery.get(0)) != "undefined") {
                    o.parentgallery.get(0).api_toggle_menu_state();
                }
            }

            function click_comments_bg(e) {
                var _t = $(this);
                var lmx = parseInt(e.clientX, 10) - _t.offset().left;
                sposarg = (lmx / _t.width()) * 100 + '%';
                var argcomm = htmlEncode('');


                if (o.skinwave_comments_links_to) {
                    return;
                }

                if (o.skinwave_comments_allow_post_if_not_logged_in == 'off' && o.skinwave_comments_account == 'none') {

                    return false;
                }

                var sw = true;

                _commentsHolder.children().each(function() {
                    var _t2 = $(this);
                    //console.info(_t2);

                    if (_t2.hasClass('placeholder') || _t2.hasClass('the-bg')) {
                        return;
                    }

                    var lmx2 = _t2.offset().left - _t.offset().left;

                    //console.info(lmx2, Math.abs(lmx2-lmx));

                    if (Math.abs(lmx2 - lmx) < 20) {
                        _commentsHolder.find('.dzstooltip-con.placeholder').remove();
                        sw = false;

                        return false;
                    }
                })


                if (!sw) {
                    return false;
                }

                var comments_offset = _commentsHolder.offset().left - cthis.offset().left;

                // console.warn(lmx, comments_offset);

                var aux3 = lmx+comments_offset - (_commentsWriter.width()/2)+7;

                var aux4 = -1;

                if(aux3<comments_offset){
                    aux4 = aux3+32;
                    aux3 = comments_offset;

                    // console.error(aux4);


                    cthis.append('<style class="comments-writter-temp-css">.audioplayer.skin-wave .comments-writer .comments-writer-inner:before{ left:'+aux4+'px  }</style>');

                }else{

                    if(aux3>tw-comments_offset - (_commentsWriter.width()/2) ){
                        aux4 = lmx - (_commentsWriter.offset().left - cthis.offset().left) + (_commentsWriter.width()/3) ;
                        aux3 = tw-comments_offset - (_commentsWriter.width()/2);

                        // console.error(lmx, _commentsWriter.offset().left - cthis.offset().left,  aux4);


                        cthis.append('<style class="comments-writter-temp-css">.audioplayer.skin-wave .comments-writer .comments-writer-inner:before{ left:'+aux4+'px  }</style>');

                    }else{

                        cthis.find('.comments-writter-temp-css').remove();
                    }
                }


                _commentsWriter.css('left', (aux3)+'px')

                if (_commentsWriter.hasClass('active') == false) {
                    _commentsWriter.css({
                        'height': _commentsWriter.find('.comments-writer-inner').eq(0).outerHeight() + 20
                    });


                    _commentsWriter.addClass('active');

                    cthis.addClass('comments-writer-active');

                    if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_handleResize != undefined) {
                        $(o.parentgallery).get(0).api_handleResize();
                    }
                }

                if (o.skinwave_comments_account != 'none') {
                    cthis.find('input[name=comment-email]').remove();
                }

                _commentsHolder.find('.dzstooltip-con.placeholder').remove();
                _commentsHolder.append('<span class="dzstooltip-con placeholder" style="left:' + sposarg + ';"><div class="the-avatar" style="background-image: url(' + o.skinwave_comments_avatar + ')"></div></span>');



                //cthis.unbind('focusout', blur_ap);
                //cthis.bind('blur', blur_ap);
            }

            function click_cancel_comment(e) {
                hide_comments_writer();
            }

            function click_submit_comment(e) {

                var comm_author = '';
                if (cthis.find('input[name=comment-email]').length > 0) {
                    var regex_mail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                    if (regex_mail.test(cthis.find('input[name=comment-email]').eq(0).val()) == false) {
                        alert('please insert email, your email is just used for gravatar. it will not be sent or stored anywhere');
                        return false;
                    }

                    comm_author = String(cthis.find('input[name=comment-email]').eq(0).val()).split('@')[0];
                    o.skinwave_comments_account = comm_author;
                    //console.info(comm_author);
                    o.skinwave_comments_avatar = 'https://secure.gravatar.com/avatar/' + MD5(String(cthis.find('input[name=comment-email]').eq(0).val()).toLowerCase()) + '?s=20';
                } else {

                }
                comm_author = o.skinwave_comments_account;

                var aux = '';



                if (o.skinwave_comments_process_in_php != 'on') {

                    // -- process the comment now, in javascript

                    aux += '<span class="dzstooltip-con" style="left:' + sposarg + '"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black" style="width: 250px;"><span class="the-comment-author">@' + comm_author + '</span> says:<br>';
                    aux += htmlEncode(cthis.find('*[name=comment-text]').eq(0).val());


                    aux += '</span><div class="the-avatar" style="background-image: url(' + o.skinwave_comments_avatar + ')"></div></span>';
                } else {



                    // -- process php

                    aux += cthis.find('*[name=comment-text]').eq(0).val();
                }


                cthis.find('*[name=comment-text]').eq(0).val('');





                skinwave_comment_publish(aux)

                hide_comments_writer();

                if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_player_commentSubmitted != undefined) {
                    $(o.parentgallery).get(0).api_player_commentSubmitted();
                }




                return false;
            }

            function hide_comments_writer() {

                //console.log(_commentsWriter);
                cthis.removeClass('comments-writer-active');
                _commentsHolder.find('.dzstooltip-con.placeholder').remove();
                _commentsWriter.removeClass('active');
                _commentsWriter.css({
                    'height': 0
                })


                if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_handleResize != undefined) {
                    $(o.parentgallery).get(0).api_handleResize();
                }

                setTimeout(function(){

                    cthis.find('.comments-writter-temp-css').remove();;
                },300);
                //cthis.unbind('focusout', blur_ap);
            }

            function check_yt_ready() {
                //console.info(loaded);
                if (loaded == true) {
                    return;
                }
                //console.log('ceva');
                //var player;
                _cmedia = new YT.Player('ytplayer_' + cthisId + '', {
                    height: '200',
                    width: '200',
                    videoId: cthis.attr('data-source'),
                    playerVars: {
                        origin: ''
                    },
                    events: {
                        'onReady': check_yt_ready_phase_two,
                        'onStateChange': change_yt_state
                    }
                });
                //init_loaded();
            }

            function check_yt_ready_phase_two(arg) {

                //console.log(arg);
                init_loaded();
            }

            function change_yt_state(arg) {
                //console.log(arg);
            }

            function check_ready(pargs) {
                // console.log('check_ready()', cthis, _cmedia, _cmedia.readyState);
                //=== do a little ready checking



                var margs = {

                    'do_not_autoplay': false
                };

                if(o.fakeplayer && is_ios()){
                    return false;
                }


                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                // console.log(_cmedia.readyState);
                if (type == 'youtube') {

                    init_loaded(margs);
                } else {
                    if (typeof(_cmedia) != 'undefined') { //|| o.type=='soundcloud'

                        //console.info(_cmedia.readyState, o.type, is_safari());


                        //                        return false;
                        if (_cmedia.nodeName != "AUDIO" || o.type == 'shoutcast') {
                            init_loaded(margs);
                        } else {
                            if (is_safari()) {

                                if (_cmedia.readyState >= 1) {
                                    //console.info("CALL INIT LOADED FROM ",_cmedia.readyState);
                                    init_loaded(margs);
                                    clearInterval(inter_checkReady);

                                    if (o.action_audio_loaded_metadata) {
                                        o.action_audio_loaded_metadata(cthis);
                                    }
                                }
                            } else {
                                if (_cmedia.readyState >= 2) {
                                    //console.info("CALL INIT LOADED FROM ",_cmedia.readyState);
                                    init_loaded(margs);
                                    clearInterval(inter_checkReady);


                                    // console.info(o.action_audio_loaded_metadata)
                                    if (o.action_audio_loaded_metadata) {
                                        o.action_audio_loaded_metadata(cthis);
                                    }
                                }
                            }

                        }
                    }

                }

            }

            function show_scrubbar() {

                // return false;


                setTimeout(function(){


                    cthis.addClass('scrubbar-loaded');
                    // _scrubbar.css({
                    //     'opacity':'1'
                    // });
                    //
                    // setTimeout(function(){
                    //
                    //     _scrubbar.css('opacity','');
                    // },500);
                },1000);
            }

            function wave_mode_canvas_try_to_get_pcm(pargs){


                var margs = {

                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                if(src_real_mp3=='fake'){
                    return false;
                }

                if (cthis.attr('data-pcm')) {


                } else {

                    var data = {
                        action: 'dzsap_get_pcm',
                        postdata: '1',
                        source: cthis.attr('data-source'),
                        playerid: pcm_identifier
                    };



                    // console.error("TRY TO GET PCM");



                    if (o.settings_php_handler) {
                        $.ajax({
                            type: "POST",
                            url: o.settings_php_handler,
                            data: data,
                            success: function(response) {
                                //if(typeof window.console != "undefined" ){ console.log('Ajax - get - comments - ' + response); }

                                // console.groupCollapsed("receivedPCM");
                                // console.info(response);
                                // console.groupEnd();

                                if(response){

                                    if(response!='0' && response.indexOf(',')>-1){

                                        cthis.attr('data-pcm', response);
                                        pcm_is_real=true;

                                        if(_scrubbar.css('opacity')=='0'){

                                        }

                                        setTimeout(function(){


                                            cthis.addClass('scrubbar-loaded');
                                            calculate_dims_time();
                                            setTimeout(function(){


                                            },100);
                                        },100);
                                        // show_scrubbar();
                                    }else{

                                        pcm_try_to_generate=true;
                                    }

                                    // console.info('pcm_try_to_generate - ',pcm_try_to_generate);
                                }else{

                                    pcm_try_to_generate=true;
                                }

                            },
                            error: function(arg) {
                                if (typeof window.console != "undefined") {
                                    console.log('Got this from the server: ' + arg, arg);
                                };
                            }
                        });
                        pcm_try_to_generate=false;
                    }else{

                    }
                }
            }


            function init_generate_wave_data(pargs) {





                var margs = {
                    'call_from' : 'default'
                    ,'call_attempt' : 0
                };


                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                if(pcm_is_real){
                    return false;
                }

                if(src_real_mp3=='fake'){
                    return false;
                }

                console.info('init_generate_wave_data(pargs)',margs, cthis);


                if(pcm_try_to_generate){

                }else{
                    setTimeout(function(){

                        margs.call_attempt++;

                        if(margs.call_attempt<10){

                            init_generate_wave_data(margs);
                        }

                    },1000)
                    return false;
                }


                // console.info('init_generate_wave_data', margs);


                // console.info('init_generate_wave_data', cthis.attr('data-source'));
                if (window.WaveSurfer) {
                    console.info('wavesurfer already loaded');
                    generate_wave_data({
                        'call_from': 'wavesurfer already loaded'
                    });
                } else {
                    var scripts = document.getElementsByTagName("script");


                    var baseUrl = '';
                    for (var i23 in scripts) {
                        if (scripts[i23].src.indexOf('audioplayer.js') > -1) {

                            break;
                        }
                    }
                    var baseUrl_arr = String(scripts[i23].src).split('/');
                    for (var i24 = 0; i24 < baseUrl_arr.length - 1; i24++) {
                        baseUrl += baseUrl_arr[i24] + '/';
                    }

                    var url = baseUrl + 'wavesurfer.js';
                    //console.warn(scripts[i23], baseUrl, url);




                    if(o.pcm_notice=='on'){

                        cthis.addClass('errored-out');
                        cthis.append('<div class="feedback-text pcm-notice">please wait while pcm data is generated.. </div>');
                    }



                    // console.info('load wavesurfer');
                    $.ajax({
                        url: url,
                        dataType: "script",
                        success: function(arg) {
                            //console.info(arg);

                            // cthis.append('')





                            generate_wave_data({
                                'call_from': 'load_script'
                            });




                        }
                    });
                }
            }




            function generate_wave_data(pargs) {


                var margs = {
                    call_from: 'default'
                }

                if (pargs) {
                    $.extend(margs, pargs);
                }



                // console.info('generate_wave_data margs - ', margs);




                if(src_real_mp3!='fake'){

                }else{
                    return false;
                }


                if(window.dzsap_generating_pcm ){
                    setTimeout(function(){
                        generate_wave_data(margs)
                    },1000);

                    return false;
                }


                window.dzsap_generating_pcm = true;

                // console.info('generate_wave_data margs - ', margs);


                // console.info(' generate_wave_data()', src_real_mp3);

                var id = 'wavesurfer' + Math.ceil(Math.random() * 1000);
                cthis.append('<div id="' + id + '" class="hidden"></div>');

                var wavesurfer = WaveSurfer.create({
                    container: '#' + id,
                    normalize: true
                });


                // console.info(String(cthis.attr('data-source')).indexOf('https://soundcloud.com'));
                if (String(cthis.attr('data-source')).indexOf('https://soundcloud.com') == 0 || cthis.attr('data-source') == 'fake') {
                    return;
                }
                if (String(cthis.attr('data-source')).indexOf('https://api.soundcloud.com') == 0) {}


                console.info(' src_real_mp3 - '+src_real_mp3, src_real_mp3);
                try{
                    wavesurfer.load(src_real_mp3);
                }catch(err){
                    console.warn("WAVE SURFER NO LOAD");
                }


                function send_pcm(ar_str){

                    cthis.attr('data-pcm', ar_str);
                    if (_feed_fakeButton && _feed_fakeButton.attr) {
                        _feed_fakeButton.attr('data-pcm', ar_str);
                    }


                    // console.info("which is fake player ? ", cthis, o.fakeplayer, _feed_fakePlayer);





                    cthis.find('.pcm-notice').fadeOut("fast");
                    cthis.removeClass('errored-out');






                    // console.info('generating wave data for '+cthis.attr('data-source'));
                    if (pcm_identifier == '') {
                        pcm_identifier = cthis.attr('data-source');


                        if (original_real_mp3) {
                            pcm_identifier = original_real_mp3;
                        }
                    }
                    console.info(' pcm_identifier- ', pcm_identifier);




                    var data = {
                        action: 'dzsap_submit_pcm',
                        postdata: ar_str,
                        playerid: pcm_identifier
                    };


                    window.dzsap_generating_pcm = false;


                    if (o.settings_php_handler) {


                        $.ajax({
                            type: "POST",
                            url: o.settings_php_handler,
                            data: data,
                            success: function(response) {

                            }
                        });
                    }
                }

                wavesurfer.on('ready', function() {
                    //            wavesurfer.play();

                    var accuracy = 1000;

                    if(_cmedia  && _cmedia.duration && _cmedia.duration>1000){

                        accuracy= 50;
                    }

                    // console.log(_cmedia, _cmedia.duration);
                    var ar_str = wavesurfer.exportPCM(256, 1000, true);

                    // console.groupCollapsed("new ar_str");
                    // console.info('new ar_str - ' , ar_str);
                    //
                    // console.groupEnd();

                    send_pcm(ar_str);



                });

                wavesurfer.on('error', function() {
                    //            wavesurfer.play();

                    console.info("WAVE SURFER ERROR !!!");

                    var default_pcm = [];

                    for (var i3 = 0; i3 < 1000; i3++) {
                        default_pcm[i3] = Math.random();
                    }
                    default_pcm = JSON.stringify(default_pcm);

                    send_pcm(default_pcm);

                });
                generate_wave_data_animate(cthis.attr('data-pcm'));



            }



            function generate_wave_data_animate(argpcm) {


                _scrubbar.find('.scrub-bg-img,.scrub-prog-img').addClass('transitioning-out');
                _scrubbar.find('.scrub-bg-img,.scrub-prog-img').animate({
                    'opacity': 0
                }, {
                    queue: false,
                    duration: 300
                });

                setup_structure_scrub_canvas({
                    'prepare_for_transition_in': true
                });



                // console.info('what is canvas width??',_scrubbarprog_canvas, _scrubbar.find('.scrub-bg-img').width());


                // console.groupCollapsed("'generate_wave_data_animate'");
                // console.info(_scrubbarbg_canvas.eq(0), argpcm, o.design_wave_color_bg, o.design_wave_color_progress);
                // console.groupEnd();

                // console.info("#"+o.design_wave_color_bg, '#'+o.design_wave_color_progress, _scrubbarprog_canvas.width());
                draw_canvas(_scrubbarbg_canvas.get(0), argpcm, "#" + o.design_wave_color_bg);
                draw_canvas(_scrubbarprog_canvas.get(0), argpcm, '#' + o.design_wave_color_progress);


                _scrubbar.find('.scrub-bg-img.transitioning-in,.scrub-prog-img.transitioning-in').animate({
                    'opacity': 1
                }, {
                    queue: false,
                    duration: 300,
                    complete: function() {
                        var _con = $(this).parent();

                        // console.info(_con);
                        // console.info("REMOVING",_con.children('.transitioning-out') );
                        _con.children('.transitioning-out').remove();
                        _con.children('.transitioning-in').removeClass('transitioning-in');
                    }
                });


                pcm_is_real = true;

                show_scrubbar();
            }

            function struct_generate_thumb(){

                // return false;
                if (cthis.attr('data-thumb') ) {


                    cthis.addClass('has-thumb');
                    var aux_thumb_con_str = '';

                    if (cthis.attr('data-thumb_link')) {
                        aux_thumb_con_str += '<a href="' + cthis.attr('data-thumb_link') + '"';
                    } else {
                        aux_thumb_con_str += '<div';
                    }
                    aux_thumb_con_str += ' class="the-thumb-con"><div class="the-thumb" style=" background-image:url(' + cthis.attr('data-thumb') + ')"></div>';


                    if (cthis.attr('data-thumb_link')) {
                        aux_thumb_con_str += '</a>';
                    } else {
                        aux_thumb_con_str += '</div>';
                    }


                    if (o.design_skin != 'skin-customcontrols') {
                        if (o.design_skin == 'skin-wave' && o.skinwave_mode == 'small') {

                            _apControlsLeft.prepend(aux_thumb_con_str);
                        } else if (o.design_skin == 'skin-steel') {


                            _apControlsLeft.append(aux_thumb_con_str);
                        } else {

                            _audioplayerInner.prepend(aux_thumb_con_str);
                        }
                    }

                    _theThumbCon = _audioplayerInner.children('.the-thumb-con').eq(0);
                }else{

                    cthis.removeClass('has-thumb');
                }
            }

            function setup_structure() {
                //alert('ceva');
                cthis.append('<div class="audioplayer-inner"></div>');
                _audioplayerInner = cthis.children('.audioplayer-inner');
                _audioplayerInner.append('<div class="the-media"></div>');


                if (o.design_skin != 'skin-customcontrols') {

                    _audioplayerInner.append('<div class="ap-controls"></div>');
                }
                _theMedia = _audioplayerInner.children('.the-media').eq(0);
                _apControls = _audioplayerInner.children('.ap-controls').eq(0);


                if(cthis.attr('data-wrapper-image')){
                    var img = new Image();

                    img.onload = function(){
                        console.info(this, this.src);

                        cthis.prepend('<div class="zoomsounds-bg" style="background-image: url('+this.src+'); "></div>');
                        setTimeout(function(){

                            cthis.children('.zoomsounds-bg').addClass('loaded');

                            //var tw = cthis.width();

                            if(tw>300){
                                tw = 300;
                            }

                            if(cthis.hasClass('zoomsounds-wrapper-bg-bellow')){

                                cthis.css('padding-top', tw - _audioplayerInner.outerHeight())
                            }
                        },100);
                    }

                    img.src = cthis.attr('data-wrapper-image');
                }


                var aux_str_scrubbar = '<div class="scrubbar">';
                var aux_str_con_controls = '';
                var aux_str_con_controls_part2 = '';


                aux_str_scrubbar += '<div class="scrub-bg"></div><div class="scrub-buffer"></div><div class="scrub-prog"></div><div class="scrubBox"></div><div class="scrubBox-prog"></div><div class="scrubBox-hover"></div>';

                if (o.design_skin == 'skin-wave' && o.skinwave_enableReflect == 'on') {
                    aux_str_scrubbar += '<div class="scrub-bg-reflect"></div>';
                    aux_str_scrubbar += '<div class="scrub-prog-reflect"></div>';

                }
                if (o.design_skin == 'skin-wave' && o.disable_timer != 'on') {
                    aux_str_scrubbar += '<div class="total-time">00:00</div><div class="curr-time">00:00</div>';

                }

                if (sample_perc_start) {

                    aux_str_scrubbar += '<div class="sample-block-start" style="width: ' + (sample_perc_start * 100) + '%"></div>'
                }
                if (sample_perc_end) {

                    aux_str_scrubbar += '<div class="sample-block-end" style="left: ' + (sample_perc_end * 100) + '%; width: ' + (100 - (sample_perc_end * 100)) + '%"></div>'
                }

                aux_str_scrubbar += '</div>'; // -- end scrubbar


                var struct_con_playpause ='';



                if(o.settings_extrahtml_before_play_pause){
                    struct_con_playpause+=o.settings_extrahtml_before_play_pause;


                }
                // console.info(cthis.find('.feed-dzsap-before-playpause'));
                if(cthis.find('.feed-dzsap-before-playpause').length){
                    struct_con_playpause+=cthis.find('.feed-dzsap-before-playpause').eq(0).html();
                    cthis.find('.feed-dzsap-before-playpause').remove();

                }

                struct_con_playpause+='<div class="con-playpause">';



                struct_con_playpause+='<div class="playbtn"><div class="the-icon-bg"></div><div class="play-icon">';







                // console.info("HMM dada", cthis);
                if(cthis.hasClass('button-aspect-noir')){
                    // console.info("HMM dada2", cthis);

                    // struct_con_playpause+='<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13.75px" height="15.812px" viewBox="-1.5 -1.844 13.75 15.812" enable-background="new -1.5 -1.844 13.75 15.812" xml:space="preserve"> <g> <path fill="#D2D6DB" d="M11.363,5.662c0.603,0.375,0.592,0.969-0.028,1.317L0.049,13.291c-0.624,0.351-1.131,0.05-1.131-0.664 V-0.782c0-0.711,0.495-0.988,1.1-0.611L11.363,5.662z"/> </g> </svg> ';
                    // struct_con_playpause+='<svg class="svg-icon" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10.455px" height="12.352px" viewBox="0 0 10.455 12.352" enable-background="new 0 0 10.455 12.352" xml:space="preserve"> <g> <path fill="#D2D6DB" d="M9.93,5.973c0.468,0.29,0.457,0.748-0.023,1.016l-8.709,4.874c-0.48,0.269-0.873,0.038-0.873-0.512V1 c0-0.55,0.382-0.762,0.849-0.472L9.93,5.973z"/> </g> </svg> ';
                    struct_con_playpause+=svg_play_icon;
                }

                struct_con_playpause+='</div>';
                struct_con_playpause+='</div>'; // -- end playbtn


                struct_con_playpause+='<div class="pausebtn" style="display:none"><div class="the-icon-bg"></div><div class="pause-icon"><div class="pause-part-1"></div><div class="pause-part-2"></div>';


                if(cthis.hasClass('button-aspect-noir')){
                    // console.info("HMM dada2", cthis);

                    // struct_con_playpause+='<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13.75px" height="15.812px" viewBox="-1.5 -1.844 13.75 15.812" enable-background="new -1.5 -1.844 13.75 15.812" xml:space="preserve"> <g> <path fill="#D2D6DB" d="M11.363,5.662c0.603,0.375,0.592,0.969-0.028,1.317L0.049,13.291c-0.624,0.351-1.131,0.05-1.131-0.664 V-0.782c0-0.711,0.495-0.988,1.1-0.611L11.363,5.662z"/> </g> </svg> ';
                    struct_con_playpause+=' <svg class="svg-icon" version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="13px" viewBox="0 0 13.415 16.333" enable-background="new 0 0 13.415 16.333" xml:space="preserve"> <path fill="#D2D6DB" d="M4.868,14.59c0,0.549-0.591,0.997-1.322,0.997H2.2c-0.731,0-1.322-0.448-1.322-0.997V1.618 c0-0.55,0.592-0.997,1.322-0.997h1.346c0.731,0,1.322,0.447,1.322,0.997V14.59z"/> <path fill="#D2D6DB" d="M12.118,14.59c0,0.549-0.593,0.997-1.324,0.997H9.448c-0.729,0-1.322-0.448-1.322-0.997V1.619 c0-0.55,0.593-0.997,1.322-0.997h1.346c0.731,0,1.324,0.447,1.324,0.997V14.59z"/> </svg>  ';
                }


                struct_con_playpause+='</div>';// -- end pause-icon
                struct_con_playpause+='</div>'; // -- end pausebtn




                struct_con_playpause+='';



                if(o.design_skin=='skin-wave'){
                    struct_con_playpause+=o.skinwave_preloader_code;
                }

                struct_con_playpause+='</div>';


                if(cthis.find('.feed-dzsap-after-playpause').length){
                    struct_con_playpause+=cthis.find('.feed-dzsap-after-playpause').eq(0).html();


                    cthis.find('.feed-dzsap-after-playpause').remove();
                }


                // struct_con_playpause = '';




                aux_str_con_controls += '<div class="con-controls"><div class="the-bg"></div>'+struct_con_playpause;


                if (o.settings_extrahtml_in_float_left) {
                    aux_str_con_controls += o.settings_extrahtml_in_float_left;
                }


                //console.info(o.disable_timer, aux_str_con_controls);
                if (o.design_skin != 'skin-wave' && o.disable_timer != 'on') {
                    aux_str_con_controls += '<div class="curr-time">00:00</div><div class="total-time">00:00</div>';

                }



                if (o.design_skin == 'skin-default' || o.design_skin == 'skin-wave') {

                    aux_str_con_controls += '<div class="ap-controls-right">';
                    if (o.disable_volume != 'on') {
                        aux_str_con_controls += '<div class="controls-volume"><div class="volumeicon"></div><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div>';
                    }


                    if (o.settings_extrahtml_in_float_right) {
                        aux_str_con_controls += o.settings_extrahtml_in_float_right;
                    }

                    aux_str_con_controls += '</div>';
                    aux_str_con_controls += '<div class="clear"></div>';


                }

                aux_str_con_controls += '</div>'; // -- end con-controls




                //console.info(o.disable_timer, aux_str_con_controls);


                if (o.design_skin == 'skin-wave' && o.skinwave_mode == 'small') {
                    aux_str_con_controls = '<div class="the-bg"></div><div class="ap-controls-left">'+struct_con_playpause+'</div>'+aux_str_scrubbar+'<div class="ap-controls-right"><div class="controls-volume"><div class="volumeicon"></div><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div></div>';


                    _apControls.append(aux_str_con_controls);



                } else {

                    if (o.design_skin == 'skin-aria' || o.design_skin == 'skin-silver' || o.design_skin == 'skin-redlights' || o.design_skin == 'skin-steel') {


                        var playbtn_svg = '<svg version="1.2" baseProfile="tiny" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="30px" viewBox="0 0 25 30" xml:space="preserve"> <path d="M24.156,13.195L2.406,0.25C2.141,0.094,1.867,0,1.555,0C0.703,0,0.008,0.703,0.008,1.562H0v26.875h0.008 C0.008,29.297,0.703,30,1.555,30c0.32,0,0.586-0.109,0.875-0.266l21.727-12.93C24.672,16.375,25,15.727,25,15 S24.672,13.633,24.156,13.195z"/> </svg>';
                        var pausebtn_svg = '<svg version="1.2" baseProfile="tiny" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="30px" viewBox="0 0 25 30" xml:space="preserve"> <path d="M9.812,29.7c0,0.166-0.178,0.3-0.398,0.3H2.461c-0.22,0-0.398-0.134-0.398-0.3V0.3c0-0.166,0.178-0.3,0.398-0.3h6.953 c0.22,0,0.398,0.134,0.398,0.3V29.7z"/> <path d="M23.188,29.7c0,0.166-0.178,0.3-0.398,0.3h-6.953c-0.22,0-0.398-0.134-0.398-0.3V0.3c0-0.166,0.178-0.3,0.398-0.3h6.953 c0.22,0,0.398,0.134,0.398,0.3V29.7z"/> </svg>';

                        if (o.design_skin == 'skin-silver') {
                            playbtn_svg = '<svg version="1.2" baseProfile="tiny" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="30px" viewBox="0 0 25 30" xml:space="preserve"> <path d="M24.156,13.195L2.406,0.25C2.141,0.094,1.867,0,1.555,0C0.703,0,0.008,0.703,0.008,1.562H0v26.875h0.008 C0.008,29.297,0.703,30,1.555,30c0.32,0,0.586-0.109,0.875-0.266l21.727-12.93C24.672,16.375,25,15.727,25,15 S24.672,13.633,24.156,13.195z"/> </svg>';
                            pausebtn_svg = '<svg version="1.2" baseProfile="tiny" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="30px" viewBox="0 0 25 30" xml:space="preserve"> <path d="M9.812,29.7c0,0.166-0.178,0.3-0.398,0.3H2.461c-0.22,0-0.398-0.134-0.398-0.3V0.3c0-0.166,0.178-0.3,0.398-0.3h6.953 c0.22,0,0.398,0.134,0.398,0.3V29.7z"/> <path d="M23.188,29.7c0,0.166-0.178,0.3-0.398,0.3h-6.953c-0.22,0-0.398-0.134-0.398-0.3V0.3c0-0.166,0.178-0.3,0.398-0.3h6.953 c0.22,0,0.398,0.134,0.398,0.3V29.7z"/> </svg>';
                        }

                        if (o.design_skin == 'skin-redlights' || o.design_skin == 'skin-steel') {
                            playbtn_svg = '';
                            pausebtn_svg = '';
                        }

                        aux_str_con_controls = '<div class="the-bg"></div><div class="ap-controls-left"><div class="con-playpause"><div class="playbtn"><div class="play-icon">' + playbtn_svg + '</div><div class="play-icon-hover"></div></div><div class="pausebtn" style="display:none"><div class="pause-icon">' + pausebtn_svg + '</div><div class="pause-icon-hover"></div></div></div>';


                        if (o.design_skin == 'skin-silver') {

                            aux_str_con_controls += '<div class="curr-time">00:00</div>';
                        }


                        aux_str_con_controls += '</div>';

                        //console.info(o.settings_extrahtml_in_float_right);


                        if (o.settings_extrahtml_in_float_right) {
                            aux_str_con_controls += '<div class="controls-right">' + o.settings_extrahtml_in_float_right + '</div>';

                            //console.info(o._gall)
                            //console.info('dada');

                            if (o.design_skin == 'skin-redlights') {

                                //console.info(o.parentgallery, o.parentgallery.get(0).api_skin_redlights_give_controls_right_to_all);
                                if (o.parentgallery && o.parentgallery.get(0).api_skin_redlights_give_controls_right_to_all) {
                                    o.parentgallery.get(0).api_skin_redlights_give_controls_right_to_all();
                                }
                            }
                        }
                        //console.info('ceva');


                        aux_str_con_controls += '<div class="ap-controls-right">';

                        if (o.design_skin == 'skin-silver') {

                            aux_str_con_controls += '<div class="controls-volume controls-volume-vertical"><div class="volumeicon"></div><div class="volume-holder"><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div></div></div>';

                            if (o.disable_timer != 'on') {
                                aux_str_con_controls += '<div class="total-time">00:00</div>';
                            }

                            aux_str_con_controls += '</div>' + aux_str_scrubbar;
                        } else {



                            if (o.design_skin == 'skin-redlights') {

                                if (o.disable_volume != 'on') {
                                    aux_str_con_controls += '<div class="controls-volume"><div class="volumeicon"></div><div class="volume_static"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="12px" viewBox="0 0 57 12" enable-background="new 0 0 57 12" xml:space="preserve"> <rect y="9" fill="#414042" width="3" height="3"/> <rect x="6" y="8" fill="#414042" width="3" height="4"/> <rect x="12" y="7" fill="#414042" width="3" height="5"/> <rect x="18" y="5.958" fill="#414042" width="3" height="6"/> <rect x="24" y="4.958" fill="#414042" width="3" height="7"/> <rect x="30" y="4" fill="#414042" width="3" height="8"/> <rect x="36" y="3" fill="#414042" width="3" height="9"/> <rect x="42" y="2" fill="#414042" width="3" height="10"/> <rect x="48" y="1" fill="#414042" width="3" height="11"/> <rect x="54" fill="#414042" width="3" height="12"/> </svg></div><div class="volume_active"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="57px" height="12px" viewBox="0 0 57 12" enable-background="new 0 0 57 12" xml:space="preserve"> <rect y="9" fill="#414042" width="3" height="3"/> <rect x="6" y="8" fill="#414042" width="3" height="4"/> <rect x="12" y="7" fill="#414042" width="3" height="5"/> <rect x="18" y="5.958" fill="#414042" width="3" height="6"/> <rect x="24" y="4.958" fill="#414042" width="3" height="7"/> <rect x="30" y="4" fill="#414042" width="3" height="8"/> <rect x="36" y="3" fill="#414042" width="3" height="9"/> <rect x="42" y="2" fill="#414042" width="3" height="10"/> <rect x="48" y="1" fill="#414042" width="3" height="11"/> <rect x="54" fill="#414042" width="3" height="12"/> </svg></div><div class="volume_cut"></div></div>';
                                }

                                aux_str_con_controls += '<div class="clear"></div>';
                            }

                            aux_str_con_controls += aux_str_scrubbar;


                            if (o.disable_timer != 'on') {
                                aux_str_con_controls += '<div class="total-time">00:00</div>';
                            }
                        }







                        if (o.design_skin == 'skin-silver') {

                        } else {
                            aux_str_con_controls += '</div>';
                        }


                        _apControls.append(aux_str_con_controls);



                    } else {





                        if (cthis.hasClass('skin-wave-mode-alternate')) {

                            _apControls.append(aux_str_con_controls + aux_str_scrubbar);

                        } else {
                            _apControls.append(aux_str_scrubbar + aux_str_con_controls);
                        }
                    }


                }

                if (_apControls.find('.ap-controls-left').length > 0) {
                    _apControlsLeft = _apControls.find('.ap-controls-left').eq(0);
                }
                if (_apControls.find('.ap-controls-right').length > 0) {
                    _apControlsRight = _apControls.find('.ap-controls-right').eq(0);
                }





                if (o.disable_timer != 'on') {
                    _currTime = cthis.find('.curr-time').eq(0);
                    _totalTime = cthis.find('.total-time').eq(0);

                    if (o.design_skin == 'skin-steel') {
                        if (_currTime.length == 0) {
                            _totalTime.before('<div class="curr-time">00:00</div> <span class="separator-slash">/</span> ');
                            //console.info('WHAT WHAT IN THE BUTT', _totalTime, _totalTime.prev(),  cthis.find('.curr-time'));

                            _currTime = _totalTime.prev().prev();

                        }
                    }

                    //console.info(_currTime, _totalTime);
                }



                if (Number(o.sample_time_total) > 0) {

                    time_total = Number(o.sample_time_total);

                    //console.info(_currTime,time_total);
                    if (_totalTime) {
                        _totalTime.html(formatTime(time_total));
                    }

                    //console.info(_totalTime.html());

                    //return false;
                }

                _scrubbar = _apControls.find('.scrubbar').eq(0);
                _conControls = _apControls.children('.con-controls');
                _conPlayPause = cthis.find('.con-playpause').eq(0);


                _controlsVolume = cthis.find('.controls-volume').eq(0);

                if (!_metaArtistCon) {
                    if (cthis.children('.meta-artist').length > 0) {
                        //console.info(cthis.hasClass('alternate-layout'));
                        if (cthis.hasClass('skin-wave-mode-alternate')) {
                            //console.info(_conControls.children().last());

                            if (_conControls.children().last().hasClass('clear')) {
                                _conControls.children().last().remove();
                            }
                            _conControls.append(cthis.children('.meta-artist'));
                        } else {
                            _audioplayerInner.append(cthis.children('.meta-artist'));
                        }

                    }
                    _audioplayerInner.find('.meta-artist').eq(0).wrap('<div class="meta-artist-con"></div>');

                    //console.info('ceva');

                    _metaArtistCon = _audioplayerInner.find('.meta-artist-con').eq(0);

                    if (o.design_skin == 'skin-wave') {

                        // console.warn(_conPlayPause);

                        if(cthis.find('.dzsap-repeat-button').length){
                            cthis.find('.dzsap-repeat-button').after(_metaArtistCon);
                        }else{


                            if(cthis.find('.dzsap-loop-button').length){
                                cthis.find('.dzsap-loop-button').after(_metaArtistCon);
                            }else {

                                _conPlayPause.after(_metaArtistCon);
                            }
                        }




                        console.info('o.skinwave_mode - ',o.skinwave_mode,_apControlsRight,_metaArtistCon);
                        if(o.skinwave_mode=='alternate'){
                            _apControlsRight.before(_metaArtistCon);
                        }


                    }
                    if (o.design_skin == 'skin-aria') {
                        _apControlsRight.prepend(_metaArtistCon);

                    }
                    if (o.design_skin == 'skin-redlights' || o.design_skin == 'skin-steel') {
                        _apControlsRight.prepend('<div class="clear"></div>');
                        _apControlsRight.prepend(_metaArtistCon);


                    }
                    if (o.design_skin == 'skin-silver') {

                        _apControlsRight.append(_metaArtistCon);
                    }
                    if (o.design_skin == 'skin-default') {


                        _apControlsRight.before(_metaArtistCon);
                    }



                }

                if (o.design_skin == 'skin-silver') {
                    _scrubbar.after(_apControlsRight);
                }



                var str_thumbh = "";
                if (design_thumbh != '') {
                    str_thumbh = ' height:' + o.design_thumbh + 'px;';
                }



                struct_generate_thumb();

                //console.info(cthis, o.disable_volume,_controlsVolume);
                if (o.disable_scrub == 'on') {
                    cthis.addClass('disable-scrubbar');
                }



                if (o.design_skin == 'skin-wave' && o.parentgallery && typeof(o.parentgallery) != 'undefined' && o.design_menu_show_player_state_button == 'on') {



                    if (o.design_skin == 'skin-wave') {

                        _apControlsRight.appendOnce('<div class="btn-menu-state player-but"> <div class="the-icon-bg"></div> <svg class="svg-icon" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13.25px" height="13.915px" viewBox="0 0 13.25 13.915" enable-background="new 0 0 13.25 13.915" xml:space="preserve"> <path d="M1.327,4.346c-0.058,0-0.104-0.052-0.104-0.115V2.222c0-0.063,0.046-0.115,0.104-0.115H11.58 c0.059,0,0.105,0.052,0.105,0.115v2.009c0,0.063-0.046,0.115-0.105,0.115H1.327z"/> <path d="M1.351,8.177c-0.058,0-0.104-0.051-0.104-0.115V6.054c0-0.064,0.046-0.115,0.104-0.115h10.252 c0.058,0,0.105,0.051,0.105,0.115v2.009c0,0.063-0.047,0.115-0.105,0.115H1.351z"/> <path d="M1.351,12.182c-0.058,0-0.104-0.05-0.104-0.115v-2.009c0-0.064,0.046-0.115,0.104-0.115h10.252 c0.058,0,0.105,0.051,0.105,0.115v2.009c0,0.064-0.047,0.115-0.105,0.115H1.351z"/> </svg>    </div></div>');
                    } else {
                        _audioplayerInner.appendOnce('<div class="btn-menu-state"></div>');
                    }
                }
                // console.info(_controlsVolume,_theThumbCon , o.skinwave_place_thumb_after_volume);
                if(o.skinwave_place_metaartist_after_volume=='on'){

                    _controlsVolume.before(_metaArtistCon);
                }

                if(o.skinwave_place_thumb_after_volume=='on'){

                    _controlsVolume.before(cthis.find('.the-thumb-con'));
                }
                //                console.info(o.embed_code);


                if (o.design_skin == 'skin-wave' && o.embed_code != '') {
                    if (o.design_skin == 'skin-wave') {

                        if(o.enable_embed_button=='on'){
                            _apControlsRight.appendOnce('<div class="btn-embed-code-con dzstooltip-con "><div class="btn-embed-code player-but"> <div class="the-icon-bg"></div> <svg class="svg-icon" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10.975px" height="14.479px" viewBox="0 0 10.975 14.479" enable-background="new 0 0 10.975 14.479" xml:space="preserve"> <g> <path d="M2.579,1.907c0.524-0.524,1.375-0.524,1.899,0l4.803,4.804c0.236-0.895,0.015-1.886-0.687-2.587L5.428,0.959 c-1.049-1.05-2.75-1.05-3.799,0L0.917,1.671c-1.049,1.05-1.049,2.751,0,3.801l3.167,3.166c0.7,0.702,1.691,0.922,2.587,0.686 L1.867,4.52c-0.524-0.524-0.524-1.376,0-1.899L2.579,1.907z M5.498,13.553c1.05,1.05,2.75,1.05,3.801,0l0.712-0.713 c1.05-1.05,1.05-2.75,0-3.8L6.843,5.876c-0.701-0.7-1.691-0.922-2.586-0.686l4.802,4.803c0.524,0.525,0.524,1.376,0,1.897 l-0.713,0.715c-0.523,0.522-1.375,0.522-1.898,0L1.646,7.802c-0.237,0.895-0.014,1.886,0.686,2.586L5.498,13.553z"/> </g> </svg></div><span class="dzstooltip transition-slidein arrow-bottom align-right skin-black " style="width: 350px; "><span style="max-height: 150px; overflow:hidden; display: block;">' + o.embed_code + '</span></span></div>');
                        }

                    } else {
                        if(o.enable_embed_button=='on') {
                            _audioplayerInner.appendOnce('<div class="btn-embed-code-con dzstooltip-con "><div class="btn-embed-code player-but "> <div class="the-icon-bg"></div> <svg class="svg-icon" version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 15 15" xml:space="preserve"> <g id="Layer_1"> <polygon fill="#E6E7E8" points="1.221,7.067 0.494,5.422 4.963,1.12 5.69,2.767 "/> <polygon fill="#E6E7E8" points="0.5,5.358 1.657,4.263 3.944,10.578 2.787,11.676 "/> <polygon fill="#E6E7E8" points="13.588,9.597 14.887,8.34 12.268,2.672 10.969,3.93 "/> <polygon fill="#E6E7E8" points="14.903,8.278 14.22,6.829 9.714,11.837 10.397,13.287 "/> </g> <g id="Layer_2"> <rect x="6.416" y="1.713" transform="matrix(0.9663 0.2575 -0.2575 0.9663 2.1699 -1.6329)" fill="#E6E7E8" width="1.805" height="11.509"/> </g> </svg></div><span class="dzstooltip transition-slidein arrow-bottom align-right skin-black " style="width: 350px; "><span style="max-height: 150px; overflow:hidden; display: block;">' + o.embed_code + '</span></span></div>');
                        }
                    }

                    cthis.on('click', '.btn-embed-code-con, .btn-embed', function() {
                        var _t = $(this);

                        // console.info(_t);
                        select_all(_t.find('.dzstooltip').get(0));
                    })
                    cthis.on('click', '.copy-embed-code-btn', function() {
                        var _t = $(this);

                        // console.info(_t);
                        select_all(_t.parent().parent().find('.dzstooltip').get(0));

                        document.execCommand('copy');
                        setTimeout(function(){

                            select_all(_t.get(0));
                        },100)
                    })

                    // cthis.on(' .btn-embed .dzstooltip').bind('click', function() {
                    //     var _t = $(this);
                    //
                    //     console.info(_t);
                    //     select_all(_t.get(0));
                    // })
                }

                if (o.design_skin == 'skin-wave') {
                    //console.info((o.design_thumbw + 20));
                    //console.info('url('+cthis.attr('data-scrubbg')+')');

                    // -- structure setup
                    if (o.skinwave_enableSpectrum != 'on') {






                        if (o.skinwave_wave_mode == 'canvas') {


                            // console.info('verify pcm - ',cthis, cthis.attr('data-pcm'));

                            if (cthis.attr('data-pcm')) {



                                // console.info('has pcm - ', cthis);



                                pcm_is_real = true;
                                setup_structure_scrub_canvas();

                            } else {

                                var default_pcm = [];

                                for (var i3 = 0; i3 < 1000; i3++) {
                                    default_pcm[i3] = Math.random();
                                }
                                default_pcm = JSON.stringify(default_pcm);

                                cthis.attr('data-pcm', default_pcm);

                                setup_structure_scrub_canvas();



                                if (o.pcm_data_try_to_generate == 'on') {


                                    init_generate_wave_data({
                                        'call_from':'pcm_data_try_to_generate .. no data-pcm'
                                    });



                                }



                            }


                        } else {

                            if (cthis.attr('data-scrubbg') != undefined) {
                                _scrubbar.children('.scrub-bg').eq(0).append('<img class="scrub-bg-img" src="' + cthis.attr('data-scrubbg') + '"/>');
                            }
                            if (cthis.attr('data-scrubprog') != undefined) {
                                _scrubbar.children('.scrub-prog').eq(0).append('<img class="scrub-prog-img" src="' + cthis.attr('data-scrubprog') + '"/>');
                            }
                            _scrubbar.find('.scrub-bg-img').eq(0).css({
                                // 'width' : _scrubbar.children('.scrub-bg').width()
                            });
                            _scrubbar.find('.scrub-prog-img').eq(0).css({
                                'width': _scrubbar.children('.scrub-bg').width()
                            });
                            //console.info(o.skinwave_enableReflect);
                            if (o.skinwave_enableReflect == 'on') {
                                _scrubbar.children('.scrub-bg-reflect').eq(0).append('<img class="scrub-bg-img-reflect" src="' + cthis.attr('data-scrubbg') + '"/>');
                                if (cthis.attr('data-scrubprog') != undefined) {
                                    _scrubbar.children('.scrub-prog-reflect').eq(0).append('<img class="scrub-prog-img-reflect" src="' + cthis.attr('data-scrubprog') + '"/>');
                                }


                                _scrubbar.find('.scrub-bg-img').eq(0).css({
                                    'transform-origin': '100% 100%'
                                })
                                _scrubbar.find('.scrub-prog-img').eq(0).css({
                                    'transform-origin': '100% 100%'
                                })
                            }
                        }



                    } else {

                        // -- spectrum

                        setup_structure_scrub_canvas(); // -- hmm


                        // -- old spectrum code
                        // _scrubbar.children('.scrub-bg').eq(0).append('<canvas class="scrub-bg-canvas" style="width: 100%; height: 100%;"></canvas><div class="wave-separator"></div>');
                        _scrubBgCanvas = cthis.find('.scrub-bg-img').eq(0);

                        console.info('_scrubBgCanvas - ',_scrubBgCanvas)
                        _scrubBgCanvasCtx = _scrubBgCanvas.get(0).getContext("2d");







                    }



                    if (o.skinwave_timer_static == 'on') {
                        if (_currTime) {
                            _currTime.addClass('static');
                        }
                        if (_totalTime) {
                            _totalTime.addClass('static');
                        }
                    }


                    _apControls.css({
                        //'height': design_thumbh
                    })



                    //console.info('setup_lsiteners()');

                    if (o.skinwave_wave_mode == 'canvas') {

                        setTimeout(function() {
                            cthis.addClass('scrubbar-loaded');
                        }, 700); // -- tbc
                    } else {

                        var auximg = new Image();

                        auximg.onload = function(e) {

                            cthis.addClass('scrubbar-loaded');
                            handleResize();
                        }

                        auximg.src = (cthis.attr('data-scrubbg'));


                        setTimeout(function() {
                            cthis.addClass('scrubbar-loaded');
                        }, 2500); // -- tbc
                    }
                }
                // --- END skin-wave


                if (cthis.hasClass('skin-minimal')) {
                    _conPlayPause.children('.playbtn').append('<canvas width="100" height="100" class="playbtn-canvas"/>');
                    skin_minimal_canvasplay = _conPlayPause.find('.playbtn-canvas').eq(0).get(0);
                    _conPlayPause.children('.pausebtn').append('<canvas width="100" height="100" class="pausebtn-canvas"/>');
                    skin_minimal_canvaspause = _conPlayPause.find('.pausebtn-canvas').eq(0).get(0);
                }

                //console.info(o.parentgallery, o.disable_player_navigation);
                if (typeof(o.parentgallery) != 'undefined' && o.disable_player_navigation != 'on') {
                    //                    _conControls.appendOnce('<div class="prev-btn"></div><div class="next-btn"></div>','.prev-btn');

                }

                if (cthis.hasClass('skin-minion')) {
                    if (cthis.find('.menu-description').length > 0) {
                        //console.log('ceva');
                        _conPlayPause.addClass('with-tooltip');
                        _conPlayPause.prepend('<span class="dzstooltip" style="left:-7px;">' + cthis.find('.menu-description').html() + '</span>');
                        //console.info(_conPlayPause.children('span').eq(0), _conPlayPause.children('span').eq(0).textWidth());
                        _conPlayPause.children('span').eq(0).css('width', _conPlayPause.children('span').eq(0).textWidth() + 10);
                    }
                }



                // === setup_structore for both flash and html5
                if (o.parentgallery && typeof(o.parentgallery) != 'undefined' && o.disable_player_navigation != 'on') {
                    //console.info('ceva', is_flashplayer , o.settings_backup_type);

                    var prev_btn_str = '<div class="prev-btn player-but"><div class="the-icon-bg"></div><svg class="svg-icon" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="14px" viewBox="0 0 12.5 12.817" enable-background="new 0 0 12.5 12.817" xml:space="preserve"> <g> <g> <g> <path fill="#D2D6DB" d="M2.581,7.375c-0.744-0.462-1.413-0.94-1.486-1.061C1.021,6.194,1.867,5.586,2.632,5.158l2.35-1.313 c0.765-0.427,1.505-0.782,1.646-0.789s0.257,1.03,0.257,1.905V7.87c0,0.876-0.051,1.692-0.112,1.817 C6.711,9.81,5.776,9.361,5.032,8.898L2.581,7.375z"/> </g> </g> </g> <g> <g> <g> <path fill="#D2D6DB" d="M6.307,7.57C5.413,7.014,4.61,6.441,4.521,6.295C4.432,6.15,5.447,5.42,6.366,4.906l2.82-1.577 c0.919-0.513,1.809-0.939,1.979-0.947s0.309,1.236,0.309,2.288v3.493c0,1.053-0.061,2.033-0.135,2.182S10.144,9.955,9.25,9.4 L6.307,7.57z"/> </g> </g> </g> </svg> </div>';

                    var next_btn_str = '<div class="next-btn player-but"><div class="the-icon-bg"></div><svg class="svg-icon" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="14px" viewBox="0 0 12.5 12.817" enable-background="new 0 0 12.5 12.817" xml:space="preserve"> <g> <g> <g> <path fill="#D2D6DB" d="M9.874,5.443c0.744,0.462,1.414,0.939,1.486,1.06c0.074,0.121-0.771,0.729-1.535,1.156L7.482,8.967 C6.719,9.394,5.978,9.75,5.837,9.756C5.696,9.761,5.581,8.726,5.581,7.851V4.952c0-0.875,0.05-1.693,0.112-1.816 c0.062-0.124,0.995,0.326,1.739,0.788L9.874,5.443z"/> </g> </g> </g> <g> <g> <g> <path fill="#D2D6DB" d="M6.155,5.248c0.893,0.556,1.696,1.129,1.786,1.274c0.088,0.145-0.928,0.875-1.847,1.389l-2.811,1.57 c-0.918,0.514-1.808,0.939-1.978,0.947c-0.169,0.008-0.308-1.234-0.308-2.287V4.66c0-1.052,0.061-2.034,0.135-2.182 s1.195,0.391,2.089,0.947L6.155,5.248z"/> </g> </g> </g> </svg>  </div>';

                    if (o.design_skin == 'skin-steel') {

                        prev_btn_str = '<div class="prev-btn player-but"><svg class="svg1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="13.325px" viewBox="0 0 10 13.325" enable-background="new 0 0 10 13.325" xml:space="preserve"> <g id="Layer_2"> <polygon opacity="0.5" fill="#E6E7E8" points="3.208,7.674 5.208,9.104 5.208,5.062 3.208,5.652 "/> </g> <g id="Layer_1"> <rect x="0.306" y="3.074" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -1.4203 4.7299)" fill="#E6E7E8" width="9.386" height="2.012"/> <rect x="0.307" y="8.29" transform="matrix(0.7072 0.707 -0.707 0.7072 8.0362 -0.8139)" fill="#E6E7E8" width="9.387" height="2.012"/> </g> </svg> <svg class="svg2"  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="13.325px" viewBox="0 0 10 13.325" enable-background="new 0 0 10 13.325" xml:space="preserve"> <g id="Layer_2"> <polygon opacity="0.5" fill="#E6E7E8" points="3.208,7.674 5.208,9.104 5.208,5.062 3.208,5.652 "/> </g> <g id="Layer_1"> <rect x="0.306" y="3.074" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -1.4203 4.7299)" fill="#E6E7E8" width="9.386" height="2.012"/> <rect x="0.307" y="8.29" transform="matrix(0.7072 0.707 -0.707 0.7072 8.0362 -0.8139)" fill="#E6E7E8" width="9.387" height="2.012"/> </g> </svg></div>';


                        next_btn_str = '<div class="next-btn player-but"><svg class="svg1" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="13.325px" viewBox="0 0 10 13.325" enable-background="new 0 0 10 13.325" xml:space="preserve"> <g id="Layer_2"> <polygon opacity="0.5" fill="#E6E7E8" points="7.035,5.695 5.074,4.292 5.074,8.257 7.035,7.678 "/> </g> <g id="Layer_1"> <rect x="0.677" y="8.234" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 15.532 12.0075)" fill="#E6E7E8" width="9.204" height="1.973"/> <rect x="0.674" y="3.118" transform="matrix(-0.7072 -0.707 0.707 -0.7072 6.1069 10.7384)" fill="#E6E7E8" width="9.206" height="1.974"/> </g> </svg><svg class="svg2" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="13.325px" viewBox="0 0 10 13.325" enable-background="new 0 0 10 13.325" xml:space="preserve"> <g id="Layer_2"> <polygon opacity="0.5" fill="#E6E7E8" points="7.035,5.695 5.074,4.292 5.074,8.257 7.035,7.678 "/> </g> <g id="Layer_1"> <rect x="0.677" y="8.234" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 15.532 12.0075)" fill="#E6E7E8" width="9.204" height="1.973"/> <rect x="0.674" y="3.118" transform="matrix(-0.7072 -0.707 0.707 -0.7072 6.1069 10.7384)" fill="#E6E7E8" width="9.206" height="1.974"/> </g> </svg></div>';

                    }




                    var auxs = prev_btn_str + next_btn_str;


                    //console.info(o.parentgallery);

                    if (o.parentgallery.hasClass('mode-showall') == false) {
                        if (o.design_skin == 'skin-wave' && o.skinwave_mode == 'small') {
                            _apControlsLeft.appendOnce(auxs, '.prev-btn');
                        } else {
                            if (o.design_skin == 'skin-wave') {

                                _conPlayPause.after(auxs);
                            } else if (o.design_skin == 'skin-steel') {

                                _apControlsLeft.prependOnce(prev_btn_str, '.prev-btn');

                                if (_apControlsLeft.children('.the-thumb-con').length > 0) {
                                    //console.info(_theThumbCon.prev());

                                    if (_apControlsLeft.children('.the-thumb-con').eq(0).length > 0) {
                                        if (_apControlsLeft.children('.the-thumb-con').eq(0).prev().hasClass('next-btn') == false) {
                                            _apControlsLeft.children('.the-thumb-con').eq(0).before(next_btn_str);
                                        }
                                    }

                                } else {

                                    _apControlsLeft.appendOnce(next_btn_str, '.next-btn');
                                }
                            } else {

                                _audioplayerInner.appendOnce(auxs, '.prev-btn');
                            }
                        }
                    }


                }





                if (cthis.children('.afterplayer').length > 0) {
                    //console.log(cthis.children('.afterplayer'));

                    cthis.append(cthis.children('.afterplayer'));
                }

                //console.log(o.settings_extrahtml);

                if (o.settings_extrahtml != '') {

                    //console.log(o.settings_extrahtml, index_extrahtml_toloads);

                    if (String(o.settings_extrahtml).indexOf('{{get_likes}}') > -1 && is_ie8() == false) {
                        index_extrahtml_toloads++;
                        ajax_get_likes();
                    }
                    if (String(o.settings_extrahtml).indexOf('{{get_plays}}') > -1 && is_ie8() == false) {
                        index_extrahtml_toloads++;
                        ajax_get_views();
                    } else {
                        // console.info('increment_views', increment_views);
                        if (increment_views === 1) {
                            ajax_submit_views();
                            increment_views = 2;
                        }
                    }

                    if (String(o.settings_extrahtml).indexOf('{{get_rates}}') > -1) {
                        index_extrahtml_toloads++;
                        ajax_get_rates();
                    }
                    o.settings_extrahtml = String(o.settings_extrahtml).replace('{{heart_svg}}',svg_heart_icon);
                    o.settings_extrahtml = String(o.settings_extrahtml).replace('{{embed_code}}',o.embed_code);

                    if (index_extrahtml_toloads == 0) {
                        //console.info('lel',cthis.find('.extra-html'))

                        cthis.find('.extra-html').addClass('active');
                    }
                    setTimeout(function() {

                        //console.info('lel',cthis.find('.extra-html'))
                        cthis.find('.extra-html').addClass('active');
                    }, 2000);

                }



                // console.error("META LODED");
                cthis.addClass('structure-setuped');

            }

            function setup_structure_scrub_canvas(pargs) {


                var margs = {
                    prepare_for_transition_in: false
                }

                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                var aux = '';
                var aux_selector = '';



                aux = '<canvas class="scrub-bg-img';



                if (margs.prepare_for_transition_in) {
                    aux += ' transitioning-in';
                }


                aux += '" ></canvas>';

                _scrubbar.children('.scrub-bg').eq(0).append(aux);




                aux_selector = '.scrub-bg-img';

                if (margs.prepare_for_transition_in) {
                    aux_selector += '.transitioning-in';
                }


                _scrubbarbg_canvas = _scrubbar.find(aux_selector).eq(0);

                // console.info(_scrubbarbg_canvas);





                aux = '<canvas class="scrub-prog-img';



                if (margs.prepare_for_transition_in) {
                    aux += ' transitioning-in';
                }


                aux += '" ></canvas>';


                _scrubbar.children('.scrub-prog').eq(0).append(aux);




                aux_selector = '.scrub-prog-img';

                if (margs.prepare_for_transition_in) {
                    aux_selector += '.transitioning-in';
                }

                _scrubbarprog_canvas = _scrubbar.find(aux_selector).eq(0);

                if(o.skinwave_enableSpectrum=='on'){
                    _scrubbarprog_canvas.hide();
                }

            }


            function draw_canvas(_arg, pcm_arr, hexcolor, pargs) {

                var margs = {
                    'call_from':'default'

                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                // console.groupCollapsed('draw_canvas');
                // console.info(_arg, pcm_arr, hexcolor, pargs);
                // console.groupEnd();

                var _canvas = $(_arg);
                var __canvas = _arg;
                var ctx = _canvas.get(0).getContext("2d");

                var ar_str = pcm_arr;
                // console.info('ar_str - ',ar_str);
                var ar = [];


                // console.info(_scrubbarprog_canvas, _scrubbar.width());
                if(_scrubbar){

                    if(_scrubbarprog_canvas){

                        _scrubbarprog_canvas.width(_scrubbar.width());
                        _arg.width = _scrubbar.width();
                        _arg.height = _scrubbar.height();
                        // _scrubbarprog_canvas.attr('width', _scrubbar.width());
                    }
                }else{

                }
                // ctx.translate(0.5, 0.5);
                // ctx.lineWidth = .5;

                ctx.imageSmoothingEnabled = false;
                ctx.imageSmoothing = false;
                ctx.imageSmoothingQuality = "high";
                ctx.webkitImageSmoothing = false;

                // console.info(ctx.canvas.clientWidth, ctx);
                // console.info(ctx.canvas.clientHeight, ctx);
                // return false;

                if(pcm_arr){

                }else{
                    setTimeout(function(){
                        // draw_canvas(_arg,pcm_arr,hexcolor);
                    },1000);

                    return false;
                }

                // console.info(ar_str, typeof(ar_str));

                if(typeof(ar_str)=='object'){
                    ar = ar_str;
                }else{
                    try{

                        ar = JSON.parse(ar_str);
                    }catch(err){
                        // console.error('parse error - ',ar_str, ar_str!='');
                    }
                }




                // console.info('ar - ', ar);


                var ratio = 1;

                var i = 0,
                    j = 0,
                    max = 0,
                    ratio = 0;

                // console.info(ar);

                // -- normalazing
                for (i = 0; i < ar.length; i++) {
                    // if (Math.abs(ar[i]) > max) {
                    //     max = Math.abs(ar[i]);
                    // }


                    if ((ar[i]) > max) {
                        max = (ar[i]);

                    }
                }


                // ratio = 1 / max;


                // console.groupCollapsed("results");
                var ar_new = [];
                for (i = 0; i < ar.length; i++) {
                    // ar[i] = parseFloat(Number(ar[i]) * parseFloat(ratio));
                    // console.log(parseFloat(Number(ar[i]) / Number(max)));


                    // ar_new[i] = parseFloat(Number(ar[i]) / Number(max));


                    ar_new[i] = parseFloat(Math.abs(ar[i]) / Number(max));


                    // if(i>0 && i<ar.length-1){
                    //
                    //     ar_new[i] = parseFloat( ((Math.abs(ar[i-1]) + Math.abs(ar[i]) + Math.abs(ar[i])) / 3) / Number(max));
                    // }else{
                    //
                    //     ar_new[i] = parseFloat(Math.abs(ar[i]) / Number(max));
                    // }
                }
                // console.groupEnd();

                // -- normalazing END




                // console.warn(162*0.005, 204/216, 204*0.0046);

                // console.info('max - ',max, ' ratio - ',ratio, 'hextoRGBA - ',hexToRgb('#222223', 0.5));
                // console.info(ar_new, ratio, ar[0], ar[1],ar[2],ar[3], ar[4]);

                ar = ar_new;



                var cw = _canvas.width();
                var ch = _canvas.height();
                var cww = __canvas.width;
                var chh = __canvas.height;


                // console.info(__canvas.width, _canvas.width());
                __canvas.width = _scrubbar.width();

                cww = __canvas.width;
                chh = __canvas.height;





                var bar_len = parseInt(o.skinwave_wave_mode_canvas_waves_number);
                var bar_space = parseFloat(o.skinwave_wave_mode_canvas_waves_padding);

                // console.info(bar_len);
                if(bar_len==1){
                    bar_len = cww/bar_len;
                }
                if(bar_len==2){
                    bar_len = cww/2;
                }
                if(bar_len==3){
                    bar_len = (cww)/3;
                }

                // console.info(cww,bar_len);


                var reflection_size = parseFloat(o.skinwave_wave_mode_canvas_reflection_size);

                // console.info('draw canvas dimenstions - ',cw,ch, cww,chh, bar_len, bar_space);
                // console.info('bar_len -  ',bar_len);

                if (cww / bar_len < 1) {
                    bar_len = Math.ceil(bar_len / 3);

                }

                // if (cww / bar_len < 2) {
                //     bar_len = Math.ceil(bar_len / 2);
                // }
                // if (cww / bar_len < 3) {
                //     bar_len = Math.ceil(cww / 4);
                // }

                // console.info('bar len - ', bar_len);


                var bar_w = Math.ceil(cww / bar_len);
                var normal_size_ratio = 1 - reflection_size;

                // console.info("bar_w - ",bar_w);

                if(bar_w == 0){
                    bar_w = 1;
                    bar_space = 0;
                }
                if(bar_w == 1){
                    bar_space = bar_space/2;
                }
                // console.info('bar_w - ', bar_w, bar_space);


                // console.info('chh - ', chh, ' normal_size_ratio - ', normal_size_ratio, 'ar - ', ar);
                var lastval = 0;




                // -- left right gradient

                //console.info('hexcolor - ',hexcolor);
                var temp_hex = hexcolor;
                temp_hex = temp_hex.replace('#','');
                var hexcolors = []; // -- hex colors array
                if(temp_hex.indexOf(',')>-1){
                    hexcolors = temp_hex.split(',');

                }



                var progress_hexcolor = '';
                var progress_hexcolors = '';


                if(margs.call_from=='spectrum'){


                    var progress_hexcolor = o.design_wave_color_progress;
                    progress_hexcolor = progress_hexcolor.replace('#','');
                    var progress_hexcolors = []; // -- hex colors array
                    if(progress_hexcolor.indexOf(',')>-1){
                        progress_hexcolors = progress_hexcolor.split(',');

                    }
                }


                var is_progress = false; // -- color the bar in progress colors

                // -- left right gradient END


                for (var i = 0; i < bar_len; i++) {


                    var searched_index = Math.ceil(i * (ar.length / bar_len));



                    if(margs.call_from=='canvas_normal_pcm_bg'){
                        // console.info(bar_len, ar.length, searched_index, ar[searched_index], ar[searched_index-1], ar[searched_index+1]);
                    }
                    // console.info(searched_index);


                    // -- we'll try to prevent
                    if(i<bar_len/5){
                        if(ar[searched_index]<0.1){
                            ar[searched_index] = 0.1;
                        }
                    }
                    if(ar.length > bar_len * 2.5 && i>0 && i<ar.length-1){
                        ar[searched_index] = Math.abs(ar[searched_index] + ar[searched_index-1] + ar[searched_index+1])/3
                    }


                    var barh = Math.abs(ar[searched_index] * chh);
                    var barh_normal = Math.abs(ar[searched_index] * chh * normal_size_ratio);

                    // -- let's try to normalize
                    barh_normal = barh_normal/1.5 + lastval/2.5;
                    lastval = barh_normal;
                    // console.info('ar searched_index', ar[searched_index], 'barh - ',barh);

                    //            var barh =


                    ctx.lineWidth = 0;

                    // console.info('bar w - ',bar_w);
                    // bar_w = parseInt(bar)

                    barh_normal = Math.floor(barh_normal);

                    // var y = chh * normal_size_ratio - barh_normal;
                    var y = Math.ceil(chh * normal_size_ratio - barh_normal);
                    if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                        // y +=1 ;
                        barh_normal ++ ;
                    }

                    // console.info(barh_normal + y)
                    // if(barh_normal + y > scrubbar_h/reflection_size){
                    //
                    //     barh_normal = scrubbar_h/reflection_size - y;
                    //
                    // }


                    ctx.beginPath();
                    ctx.rect(i * bar_w, y, bar_w - bar_space, barh_normal);

                    // console.info('coords - ',i*bar_w, parseInt(chh * normal_size_ratio - barh_normal,10), bar_w-bar_space, parseInt(barh_normal,10));

                    // console.info(hexcolor);



                    // -- left right gradient
                    // nr++;
                    //
                    // hexcolor = '#'+nr.toString(16);

                    // -- left right gradient END



                    if(margs.call_from=='spectrum'){
                        if(i/bar_len<time_curr/time_total){
                            is_progress = true;
                        }else{
                            is_progress = false;
                        }
                        if(debug_var){
                            //console.log(time_curr, time_total);
                            //console.log(i, bar_len);





                            if(i>50){

                            }

                            //console.info('is_progress - ',is_progress, progress_hexcolor, progress_hexcolors);
                        }
                        debug_var = false;
                    }



                    if(is_progress){
                        // -- only for spectrum


                        ctx.fillStyle = '#'+progress_hexcolor;

                        if(progress_hexcolors.length){
                            var gradient = ctx.createLinearGradient(0,0,0,chh);
                            gradient.addColorStop(0,'#'+progress_hexcolors[0]);
                            gradient.addColorStop(1,'#'+progress_hexcolors[1]);
                            ctx.fillStyle = gradient;
                        }
                    }else{

                        ctx.fillStyle = hexcolor;

                        if(hexcolors.length){
                            var gradient = ctx.createLinearGradient(0,0,0,chh);
                            hexcolors[0] = String(hexcolors[0]).replace('#','');
                            hexcolors[1] = String(hexcolors[1]).replace('#','');
                            gradient.addColorStop(0,'#'+hexcolors[0]);
                            gradient.addColorStop(1,'#'+hexcolors[1]);
                            ctx.fillStyle = gradient;
                        }
                    }


                    // console.info(ctx.fillStyle);

                    // -- top bottom gradient

                    // var gradient = ctx.createLinearGradient(0,0,0,chh);
                    // gradient.addColorStop(0,hexcolor);
                    // gradient.addColorStop(1,ColorLuminance(hexcolor, -0.25));
                    // ctx.fillStyle = gradient;


                    // -- top bottom gradient END


                    ctx.fill();

                    ctx.closePath();


                }


                // -- reflection

                // -- reflection
                if (reflection_size > 0) {
                    for (var i = 0; i < bar_len; i++) {



                        var searched_index = Math.ceil(i * (ar.length / bar_len));


                        // console.info(searched_index);

                        var barh = Math.abs(ar[searched_index] * chh);
                        var barh_ref = Math.abs(ar[searched_index] * chh * reflection_size);

                        // console.info('ar searched_index', ar[searched_index], 'barh - ',barh);

                        //            var barh =

                        ctx.beginPath();
                        ctx.rect(i * bar_w, chh * normal_size_ratio, bar_w - bar_space, barh_ref);

                        //            console.info('coords - ',i*bar_w, chh-( normal_size_ratio * barh ), bar_w-bar_space, normal_size_ratio * barh);



                        if(margs.call_from=='spectrum') {
                            if (i / bar_len < time_curr / time_total) {
                                is_progress = true;
                            } else {
                                is_progress = false;
                            }
                        }

                        if(is_progress){

                            if(o.skinwave_wave_mode_canvas_mode!='reflecto'){

                                ctx.fillStyle = hexToRgb(progress_hexcolor, 0.25);
                            }



                            if(progress_hexcolors.length){
                                var gradient = ctx.createLinearGradient(0,0,0,chh);
                                var aux = hexToRgb('#'+progress_hexcolors[1], 0.25);
                                if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                                    aux = hexToRgb('#'+progress_hexcolors[1], 1);
                                }
                                gradient.addColorStop(0,aux);
                                aux = hexToRgb('#'+progress_hexcolors[0], 0.25);
                                if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                                    aux = hexToRgb('#'+progress_hexcolors[0], 1);
                                }
                                gradient.addColorStop(1,aux);
                                ctx.fillStyle = gradient;
                            }
                        }else{

                            if(o.skinwave_wave_mode_canvas_mode!='reflecto'){

                                ctx.fillStyle = hexToRgb(hexcolor, 0.25);
                            }



                            if(hexcolors.length){
                                var gradient = ctx.createLinearGradient(0,0,0,chh);
                                var aux = hexToRgb('#'+hexcolors[1], 0.25);
                                if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                                    aux = hexToRgb('#'+hexcolors[1], 1);
                                }
                                gradient.addColorStop(0,aux);
                                aux = hexToRgb('#'+hexcolors[0], 0.25);
                                if(o.skinwave_wave_mode_canvas_mode=='reflecto'){
                                    aux = hexToRgb('#'+hexcolors[0], 1);
                                }
                                gradient.addColorStop(1,aux);
                                ctx.fillStyle = gradient;
                            }
                        }


                        ctx.fill();


                        ctx.closePath();

                    }
                }

                setTimeout(function(){

                    show_scrubbar();
                },100)

            }

            function ajax_get_likes(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_get_likes',
                    postdata: mainarg,
                    playerid: the_player_id
                };





                if (o.settings_php_handler) {


                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            var auxls = false;
                            if (response.indexOf('likesubmitted') > -1) {
                                response = response.replace('likesubmitted', '');
                                auxls = true;
                            }


                            if (response == '') {
                                response = 0;
                            }


                            var auxhtml = cthis.find('.extra-html').eq(0).html();
                            auxhtml = auxhtml.replace('{{get_likes}}', response);
                            cthis.find('.extra-html').eq(0).html(auxhtml);
                            index_extrahtml_toloads--;
                            if (auxls) {
                                cthis.find('.extra-html').find('.btn-like').addClass('active');
                            }



                            //console.log(index_extrahtml_toloads);
                            if (index_extrahtml_toloads == 0) {
                                cthis.find('.extra-html').addClass('active');
                            }

                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };
                            index_extrahtml_toloads--;
                            if (index_extrahtml_toloads == 0) {
                                cthis.find('.extra-html').addClass('active');
                            }
                        }
                    });
                }

            }

            function ajax_get_rates(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_get_rate',
                    postdata: mainarg,
                    playerid: the_player_id
                };


                if (o.settings_php_handler) {

                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            var auxls = false;
                            if (response.indexOf('likesubmitted') > -1) {
                                response = response.replace('likesubmitted', '');
                                auxls = true;
                            }


                            if (response == '') {
                                response = '0|0';
                            }


                            var auxresponse = response.split('|');


                            starrating_nrrates = auxresponse[1];
                            cthis.find('.extra-html .counter-rates .the-number').eq(0).html(starrating_nrrates);
                            index_extrahtml_toloads--;


                            cthis.find('.star-rating-set-clip').width(auxresponse[0] * (parseInt(cthis.find('.star-rating-bg').width(), 10) / 5));


                            //===ratesubmitted
                            if (typeof(auxresponse[2]) != 'undefined') {
                                starrating_alreadyrated = auxresponse[2];


                                if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_player_rateSubmitted != undefined) {
                                    $(o.parentgallery).get(0).api_player_rateSubmitted();
                                }
                            }


                            if (index_extrahtml_toloads <= 0) {
                                cthis.find('.extra-html').addClass('active');
                            }

                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };
                            index_extrahtml_toloads--;
                            if (index_extrahtml_toloads <= 0) {
                                cthis.find('.extra-html').addClass('active');
                            }
                        }
                    });
                }
            }

            function ajax_get_views(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_get_views',
                    postdata: mainarg,
                    playerid: the_player_id
                };



                if(data.playerid==''){
                    data.playerid = clean_string(data_source);
                }



                if (o.settings_php_handler) {

                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            // console.info(response);


                            if (response.indexOf('viewsubmitted') > -1) {
                                response = response.replace('viewsubmitted', '');
                                ajax_view_submitted = 'on';
                                increment_views = 0;
                            }

                            if (response == '') {
                                response = 0;
                            }


                            if (String(response).indexOf('{{theip') > -1) {

                                var auxa = /{\{theip-(.*?)}}/g.exec(response);
                                if (auxa[1]) {
                                    currIp = auxa[1];
                                }

                                response = response.replace(/{\{theip-(.*?)}}/g, '');


                            }


                            console.info('increment_views', increment_views);
                            if (increment_views == 1) {
                                ajax_submit_views();
                                //console.info('response iz '+response);
                                response = Number(response) + increment_views;;
                                //console.info(response);
                                increment_views = 2;
                            }

                            var auxhtml = cthis.find('.extra-html').eq(0).html();
                            auxhtml = auxhtml.replace('{{get_plays}}', response);
                            cthis.find('.extra-html').eq(0).html(auxhtml);
                            index_extrahtml_toloads--;


                            if (index_extrahtml_toloads == 0) {
                                cthis.find('.extra-html').addClass('active');
                            }

                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };
                            index_extrahtml_toloads--;
                            if (index_extrahtml_toloads == 0) {
                                cthis.find('.extra-html').addClass('active');
                            }
                        }
                    });
                }
            }


            function ajax_submit_rating(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_submit_rate',
                    postdata: mainarg,
                    playerid: the_player_id
                };

                if (starrating_alreadyrated > -1) {
                    return;
                }


                if (o.settings_php_handler) {
                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            };


                            var aux = cthis.find('.star-rating-set-clip').outerWidth() / cthis.find('.star-rating-bg').outerWidth();
                            var nrrates = parseInt(cthis.find('.counter-rates .the-number').html(), 10);

                            nrrates++;

                            var aux2 = ((nrrates - 1) * (aux * 5) + starrating_index) / (nrrates)

                            //                        console.info(aux, aux2, nrrates);
                            cthis.find('.star-rating-set-clip').width(aux2 * (parseInt(cthis.find('.star-rating-bg').width(), 10) / 5));
                            cthis.find('.counter-rates .the-number').html(nrrates);

                            if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_player_rateSubmitted != undefined) {
                                $(o.parentgallery).get(0).api_player_rateSubmitted();
                            }

                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };


                            var aux = cthis.find('.star-rating-set-clip').outerWidth() / cthis.find('.star-rating-bg').outerWidth();
                            var nrrates = parseInt(cthis.find('.counter-rates .the-number').html(), 10);

                            nrrates++;

                            var aux2 = ((nrrates - 1) * (aux * 5) + starrating_index) / (nrrates)

                            //                        console.info(aux, aux2, nrrates);
                            cthis.find('.star-rating-set-clip').width(aux2 * (parseInt(cthis.find('.star-rating-bg').width(), 10) / 5));
                            cthis.find('.counter-rates .the-number').html(nrrates);

                            if (o.parentgallery && $(o.parentgallery).get(0) != undefined && $(o.parentgallery).get(0).api_player_rateSubmitted != undefined) {
                                $(o.parentgallery).get(0).api_player_rateSubmitted();
                            }

                        }
                    });
                }
            };


            function ajax_submit_download(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_submit_download',
                    postdata: mainarg,
                    playerid: the_player_id
                };

                if (starrating_alreadyrated > -1) {
                    return;
                }

                if (o.settings_php_handler) {

                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            };


                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };


                        }
                    });
                }
            };


            function ajax_submit_views(argp) {

                // console.info('ajax_submit_views()',argp);

                var data = {
                    action: 'dzsap_submit_views',
                    postdata: 1,
                    playerid: the_player_id,
                    currip: currIp
                };


                if(cthis.attr('data-playerid-for-views')){
                    data.playerid = cthis.attr('data-playerid-for-views');
                }


                if(data.playerid==''){
                    data.playerid = clean_string(data_source);
                }

                //                console.info(ajax_view_submitted);


                if (o.settings_php_handler) {
                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            var auxnr = cthis.find('.counter-hits .the-number').html();
                            auxnr = parseInt(auxnr, 10);
                            if (increment_views != 2) {
                                auxnr++;
                            }

                            cthis.find('.counter-hits .the-number').html(auxnr);

                            ajax_view_submitted = 'on';
                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };


                            var auxlikes = cthis.find('.counter-hits .the-number').html();
                            auxlikes = parseInt(auxlikes, 10);
                            auxlikes++;
                            cthis.find('.counter-hits .the-number').html(auxlikes);

                            ajax_view_submitted = 'on';
                        }
                    });
                    ajax_view_submitted = 'on';
                }
            }

            function ajax_submit_like(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_submit_like',
                    postdata: mainarg,
                    playerid: the_player_id
                };


                if (o.settings_php_handler || cthis.hasClass('is-preview')) {

                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            cthis.find('.btn-like').addClass('active');
                            var auxlikes = cthis.find('.counter-likes .the-number').html();
                            auxlikes = parseInt(auxlikes, 10);
                            auxlikes++;
                            cthis.find('.counter-likes .the-number').html(auxlikes);
                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };


                            cthis.find('.btn-like').addClass('active');
                            var auxlikes = cthis.find('.counter-likes .the-number').html();
                            auxlikes = parseInt(auxlikes, 10);
                            auxlikes++;
                            cthis.find('.counter-likes .the-number').html(auxlikes);
                        }
                    });
                }
            }

            function ajax_retract_like(argp) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_retract_like',
                    postdata: mainarg,
                    playerid: the_player_id
                };



                if (o.settings_php_handler) {
                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            cthis.find('.btn-like').removeClass('active');
                            var auxlikes = cthis.find('.counter-likes .the-number').html();
                            auxlikes = parseInt(auxlikes, 10);
                            auxlikes--;
                            cthis.find('.counter-likes .the-number').html(auxlikes);
                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };

                            cthis.find('.btn-like').removeClass('active');
                            var auxlikes = cthis.find('.counter-likes .the-number').html();
                            auxlikes = parseInt(auxlikes, 10);
                            auxlikes--;
                            cthis.find('.counter-likes .the-number').html(auxlikes);
                        }
                    });
                }
            }

            function skinwave_comment_publish(argp) {
                // -- only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_front_submitcomment',
                    postdata: mainarg,
                    playerid: the_player_id,
                    comm_position: sposarg,
                    skinwave_comments_process_in_php: o.skinwave_comments_process_in_php,
                    skinwave_comments_avatar: o.skinwave_comments_avatar,
                    skinwave_comments_account: o.skinwave_comments_account
                };

                if (cthis.find('*[name=comment-email]').length > 0) {

                    data.email = cthis.find('*[name=comment-email]').eq(0).val();
                }



                if (o.settings_php_handler) {
                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (response.charAt(response.length - 1) == '0') {
                                response = response.slice(0, response.length - 1);
                            }
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            //console.info(data.postdata);


                            var aux = '';
                            if (o.skinwave_comments_process_in_php != 'on') {

                                // -- process the comment now, in javascript
                                aux = (data.postdata);

                            } else {

                                // -- process php
                                aux = '';


                                aux += '<span class="dzstooltip-con" style="left:' + sposarg + '"><span class="dzstooltip arrow-from-start transition-slidein arrow-bottom skin-black" style="width: 250px;"><span class="the-comment-author">@' + o.skinwave_comments_account + '</span> says:<br>';
                                aux += htmlEncode(data.postdata);


                                aux += '</span><div class="the-avatar" style="background-image: url(' + o.skinwave_comments_avatar + ')"></div></span>';


                            }

                            // console.info(aux);
                            // _commentsHolder.append(aux);

                            _commentsHolder.children().each(function(){
                                var _t2 = $(this);

                                if(_t2.hasClass('dzstooltip-con')==false){
                                    _t2.addClass('dzstooltip-con');
                                }
                            })

                            _commentsHolder.append(aux);



                            if (action_audio_comment) {
                                action_audio_comment(cthis, aux);
                            }


                            //jQuery('#save-ajax-loading').css('visibility', 'hidden');
                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };
                            _commentsHolder.append(data.postdata);
                        }
                    });
                }
            }

            function setup_media(pargs) {
                // -- order = init, setup_media, init_loaded


                //                return;


                var margs = {

                    'do_not_autoplay': false
                    ,'call_from': 'default'
                };


                if (pargs) {
                    margs = $.extend(margs, pargs);
                }
                // console.info('--- setup_media()', cthis.attr('data-source'), o.cue,ajax_view_submitted, margs, loaded, cthis, o.preload_method);

                if (o.cue == 'off') {
                    //console.info(ajax_view_submitted);
                    if (ajax_view_submitted == 'auto') {


                        // -- why is view submitted ?
                        increment_views = 1;

                        // console.info(o.settings_extrahtml);
                        if (String(o.settings_extrahtml).indexOf('{{get_plays}}') > -1) {
                            ajax_view_submitted = 'on'
                        } else {
                            ajax_view_submitted = 'off';
                        };
                    }
                }






                //console.info(type, o.type, loaded);

                if (loaded == true) {
                    return;
                }




                if (type == 'youtube') {
                    if (is_ie()) {
                        _theMedia.css({
                            'left': '-478em'
                        })
                    }

                    console.warn("HMM");
                    cthis.addClass('media-setuped');
                    cthis.addClass('meta-loaded');
                    _conPlayPause.off('click');
                    _conPlayPause.on('click', click_playpause);
                    return;
                }


                var aux1 = '';
                var aux_source = '';
                var aux9 = '';

                if(is_ios()){
                    o.preload_method='metadata';
                }

                aux1 += '<audio';
                aux1 += ' preload="'+o.preload_method+'"';
                if(o.skinwave_enableSpectrum=='on'){
                    // aux1+=' crossOrigin="anonymous"';
                    // aux1 += ' src="'+cthis.attr('data-source')+'"';
                }

                if(is_ios()){
                    if(margs.call_from=='change_media'){
                        aux+=' autoplay';
                    }
                }

                aux1 += '>';
                aux_source = '';

                // console.warn('cthis.attr("data-source")', cthis.attr('data-source'));
                if (cthis.attr('data-source')) {
                    data_source = cthis.attr('data-source');

                    // console.info('data_source'+' - '+data_source)
                    if(data_source!='fake'){

                        aux_source += '<source src="' + data_source + '" type="audio/mpeg">';
                        if (cthis.attr('data-sourceogg') != undefined) {
                            aux_source += '<source src="' + cthis.attr('data-sourceogg') + '" type="audio/ogg">';
                        }
                    }else{
                        cthis.addClass('meta-loaded meta-fake');
                    }
                }
                aux9 += '</audio>';

                //alert(is_ie8());
                if (is_ie8() && dzsap_list && dzsap_list.length > 0) {

                    str_ie8 = '&isie8=on';
                }
                if (is_flashplayer) {
                    if (o.settings_backup_type == 'light') {
                        aux1 = '<object type="application/x-shockwave-flash" data="' + o.swf_location + '" width="100" height="50" id="flashcontent_' + cthisId + '" allowscriptaccess="always" style="visibility: visible; "><param name="movie" value="ap.swf"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="media=' + cthis.attr("data-source") + "&fvid=" + cthisId + str_ie8 + '"><embed src="' + o.swf_location + '" width="100" height="100" allowScriptAccess="always"></object>';
                        cthis.addClass('lightflashbackup');
                    } else {
                        //                        console.info(cthis.attr('data-source'));
                        var str_vol = '';
                        var str_skip_buttons = '';
                        var str_design_menu_show_player_state_button = '';



                        if (o.parentgallery && typeof(o.parentgallery) != 'undefined' && o.disable_player_navigation != 'on') {
                            str_skip_buttons = '&design_skip_buttons=on';
                        }
                        if (o.parentgallery && typeof(o.parentgallery) != 'undefined' && o.design_menu_show_player_state_button != 'on') {
                            str_design_menu_show_player_state_button = '&design_menu_show_player_state_button=on';
                        }

                        if (o.disable_volume == 'on') {
                            str_vol += '&settings_enablevolume=off';
                        }





                        aux1 = '<object class="the-full-flash-backup" type="application/x-shockwave-flash" data="' + o.swffull_location + '" width="100%" height="100%" style="height:50px" id="flashcontent_' + cthisId + '" allowscriptaccess="always" style="visibility: visible; "><param name="movie" value="' + o.swffull_location + '"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"><param name="flashvars" value="media=' + cthis.attr("data-source") + "&fvid=" + cthisId + str_ie8 + str_vol + '&autoplay=' + o.autoplay + '&skinwave_mode' + o.skinwave_mode + str_skip_buttons + str_design_menu_show_player_state_button;
                        cthis.addClass('fullflashbackup');
                        if (typeof(cthis.attr("data-scrubbg")) != 'undefined') {
                            aux1 += '&scrubbg=' + cthis.attr("data-scrubbg");
                        }
                        if (typeof(cthis.attr("data-scrubprog")) != 'undefined') {
                            aux1 += '&scrubprog=' + cthis.attr("data-scrubprog");
                        }
                        if (typeof(cthis.attr("data-thumb")) != 'undefined' && cthis.attr("data-thumb") != '') {
                            aux1 += '&thumb=' + cthis.attr("data-thumb");
                        }
                        aux1 += '&settings_enablespectrum=' + o.skinwave_enableSpectrum;
                        aux1 += '&skinwave_enablereflect=' + o.skinwave_enableReflect;

                        aux1 += '&skin=' + o.design_skin;
                        aux1 += '&settings_multiplier=' + o.skinwave_spectrummultiplier;



                        aux1 += '">You need Flash Player.</object>';

                        _audioplayerInner.find('.the-thumb-con,.ap-controls,.the-media').remove();
                        _audioplayerInner.prepend(aux1);

                        if (o.design_skin == 'skin-wave') {
                            _audioplayerInner.find('.the-full-flash-backup').css("height", 200);
                        }
                        if (typeof(cthis.attr("data-thumb")) != 'undefined' && cthis.attr("data-thumb") != '') {
                            _audioplayerInner.find('.the-full-flash-backup').css("height", 200);
                        }

                        aux1 = '';

                        //return;
                    }
                }
                //<embed src="'+ o.swf_location+'" width="100" height="100" allowScriptAccess="always">
                //console.log(aux1, _theMedia);

                str_audio_element = aux1+aux_source+aux9;

                // console.info('final_aux', str_audio_element, _theMedia);

                if(margs.call_from=='change_media'){
                    if(_cwatermark && _cwatermark.pause){
                        _cwatermark.pause();
                    }
                    _theMedia.find('.the-watermark').remove();
                    _cwatermark = null;
                    if(is_ios() || is_android()){

                        // -- we append only the source to mobile devices as we need the thing to autoplay without user action

                        if(_cmedia){
                            $(_cmedia).append(aux_source);
                            if(margs.call_from=='change_media'){

                                _cmedia.addEventListener('loadedmetadata', function(e) {
                                    // console.warn('loadedmetadata', this, this.audioElement, this.duration, cthis);
                                    // console.info('add metaloaded here');

                                    cthis.addClass('meta-loaded');
                                    cthis.removeClass('meta-fake');
                                }, true);
                            }
                            if(_cmedia.load){
                                _cmedia.load();
                            }
                        }

                    }else{

                        _theMedia.append(str_audio_element);
                        _cmedia = (_theMedia.children('audio').get(0));
                    }
                }else{

                    _theMedia.append(str_audio_element);
                    _cmedia = (_theMedia.children('audio').get(0));
                }

                if(cthis.attr('data-soft-watermark')){
                    //type="audio/wav"

                    //console.info('add watermark');
                    _theMedia.append('<audio class="the-watermark" preload="metadata" loop><source src="'+cthis.attr('data-soft-watermark')+'" /></audio>');
                    _cwatermark = _theMedia.find('.the-watermark').get(0);

                    if(_cwatermark.volume){
                        _cwatermark.volume = defaultVolume * o.watermark_volume;
                    }
                    //console.info(_cwatermark);
                }

                // console.warn(margs);


                //return;
                //_theMedia.children('audio').get(0).autoplay = false;

                if (_cmedia && _cmedia.addEventListener && cthis.attr('data-source') != 'fake') {
                    _cmedia.addEventListener('error', function(e) {
                        console.info('errored out', this, this.audioElement, this.duration, e);
                        var noSourcesLoaded = (this.networkState === HTMLMediaElement.NETWORK_NO_SOURCE);
                        if (noSourcesLoaded){
                            if(cthis.hasClass('errored-out')==false){
                                console.warn("could not load audio source - ", cthis.attr('data-source'));
                                cthis.addClass('errored-out');
                                cthis.append('<div class="feedback-text">error - file does not exist.. </div>');

                                if(attempt_reload<5) {
                                    setTimeout(function () {

                                        console.info(_cmedia, _cmedia.src, cthis.attr('data-source'));

                                        _cmedia.src = '';
                                        // _cmedia.load();


                                        setTimeout(function () {

                                            // console.info(_cmedia, _cmedia.src, cthis.attr('data-source'));

                                            _cmedia.src = cthis.attr('data-source');
                                            _cmedia.load();
                                        }, 1000)


                                    }, 1000)
                                    attempt_reload++;
                                }
                            }
                        }

                    }, true);
                    _cmedia.addEventListener('loadedmetadata', function(e) {
                        // console.info('loadedmetadata', this, this.audioElement, this.duration, cthis);
                        cthis.addClass('meta-loaded');
                        cthis.removeClass('meta-fake');

                        // console.info('add metaloaded here');


                        if(margs.call_from=='change_media'){
                            if(cthis.hasClass('init-loaded')==false){
                                init_loaded({
                                    'call_from':'force_reload_change_media'
                                })
                            }
                        }
                    }, true);
                }

                if (is_flashplayer && o.settings_backup_type == 'light') {
                    setTimeout(function() {
                        _cmedia = (_theMedia.find('object').eq(0).get(0));
                    }, 500)
                }


                //console.info(cthis,type);
                if (type != 'fake') {

                    //return false;
                }

                //alert(_cmedia);


                cthis.addClass('media-setuped');
                _conPlayPause.off('click');
                _conPlayPause.on('click', click_playpause);

                if(margs.call_from=='change_media'){
                    return false;
                }


                //console.info("TRY TO CHECK READY", cthis);

                // console.info("CEVA");
                //           is_ios() ||

                if(cthis.attr('data-source')=='fake'){
                    if(is_ios() || is_android()){
                        init_loaded(margs);
                    }
                }else{



                    if (is_ios() || is_ie8() || is_flashplayer == true) {

                        // console.info("o.settings_backup_type - ", o.settings_backup_type);
                        if (o.settings_backup_type == 'full') {
                            init_loaded(margs);
                        } else {
                            setTimeout(function() {
                                init_loaded(margs);
                            }, 1000);
                        }

                    } else {

                        // -- check_ready() will fire init_loaded()
                        inter_checkReady = setInterval(function() {
                            check_ready(margs);
                        }, 50);
                        //= setInterval(check_ready, 50);
                    }

                }


                // is_ios() ||



                if(o.preload_method=='none'){

                    // console.info(window.dzsap_player_index);
                    setTimeout(function(){
                        if(_cmedia){

                            $(_cmedia).attr('preload', 'metadata');
                        }
                    },Number(window.dzsap_player_index) * 12000);
                }


                // -- hmm
                // console.info("ASSIGN HERE", cthis, _conPlayPause);


                if (o.design_skin == 'skin-customcontrols') {
                    cthis.find('.custom-play-btn,.custom-pause-btn').off('click');
                    cthis.find('.custom-play-btn,.custom-pause-btn').on('click', click_playpause);
                }

                if (o.failsafe_repair_media_element) {
                    setTimeout(function() {

                        if (_theMedia.children().eq(0).get(0) && _theMedia.children().eq(0).get(0).nodeName == "AUDIO") {
                            //console.info('ceva');
                            return false;
                        }
                        _theMedia.html('');
                        _theMedia.append(str_audio_element);

                        var aux_wasplaying = playing;

                        pause_media();
                        //return;
                        //_theMedia.children('audio').get(0).autoplay = false;
                        _cmedia = (_theMedia.children('audio').get(0));
                        if (is_flashplayer && o.settings_backup_type == 'light') {
                            setTimeout(function() {

                                _cmedia = (_theMedia.find('object').eq(0).get(0));

                            }, 10)
                        }


                        if (aux_wasplaying) {
                            aux_wasplaying = false;
                            setTimeout(function() {

                                play_media({
                                    'call_from':'aux_was_playing'
                                });
                            }, 20);
                        }
                    }, o.failsafe_repair_media_element);

                    o.failsafe_repair_media_element = '';

                }



            }

            function destroy_media() {
                //console.info("destroy_media()", cthis)
                pause_media();



                if (_cmedia) {

                    //console.log(_cmedia, _cmedia.src);
                    if (_cmedia.children) {

                        //_cmedia.children().remove();
                    }

                    //console.log(_cmedia.innerHTML);
                    if (o.type == 'audio') {
                        _cmedia.innerHTML = '';
                        _cmedia.load();
                    }
                    //console.log(_cmedia.innerHTML);

                    //_cmedia.remove();
                }

                if(is_ios() || is_android()){
                    if (_cmedia) {

                        // _cmedia.children().remove();
                        // loaded = false;
                    }
                }else{
                    if (_theMedia) {

                        _theMedia.children().remove();
                        loaded = false;
                    }
                }

            }

            function setup_listeners() {


                // console.info('setup_listeners()');


                _scrubbar.bind('mousemove', mouse_scrubbar);
                _scrubbar.bind('mouseleave', mouse_scrubbar);
                _scrubbar.bind('click', mouse_scrubbar);

                // cthis.on('');


                _controlsVolume.find('.volumeicon').bind('click', click_mute);

                if (o.design_skin == 'skin-redlights') {
                    _controlsVolume.bind('mousemove', mouse_volumebar);
                    _controlsVolume.bind('mousedown', mouse_volumebar);


                    $(document).undelegate(window, 'mouseup', mouse_volumebar);
                    $(document).delegate(window, 'mouseup', mouse_volumebar);
                } else {

                    _controlsVolume.find('.volume_active').bind('click', mouse_volumebar);
                    _controlsVolume.find('.volume_static').bind('click', mouse_volumebar);

                    if (o.design_skin == 'skin-silver') {
                        cthis.on('click', '.volume-holder',mouse_volumebar);
                    }

                }

                cthis.find('.playbtn').unbind('click');





                //                console.log('setup_listeners()');

                setTimeout(handleResize, 300);
                // setTimeout(handleResize,1000);
                setTimeout(handleResize, 2000);

                if (o.settings_trigger_resize > 0) {
                    inter_trigger_resize = setInterval(handleResize, o.settings_trigger_resize);
                }




                cthis.addClass('listeners-setuped');



                return false;

                //                console.info('ceva');
            }

            function click_like() {
                console.info('click_like()');
                var _t = $(this);
                if (cthis.has(_t).length == 0) {
                    return;
                }

                if (_t.hasClass('active')) {
                    ajax_retract_like();
                } else {
                    ajax_submit_like();
                }
            }



            function get_last_vol() {
                return last_vol;
            }

            function init_loaded(pargs) {

                if (cthis.attr('id') == 'apminimal') {
                }
                // console.warn('init_loaded()', pargs, cthis, cthis.hasClass('loaded'));
                if (cthis.hasClass('dzsap-loaded')) {
                    return;
                }

                var margs = {

                    'do_not_autoplay': false
                    ,'call_from': 'init'
                };


                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                // console.groupCollapsed('init_loaded()');
                // console.info('', margs);
                //
                // console.warn(cthis, cthis.hasClass('loaded'));
                // console.groupEnd();



                if (is_flashplayer == false) {


                } else {
                    if (o.settings_backup_type == 'light') {

                        if (typeof(_cmedia) != "undefined" && _cmedia.fn_getSoundDuration) {
                            eval("totalDuration = parseFloat(_cmedia.fn_getsoundduration" + cthisId + "())");
                        }
                    }
                }
                if (typeof(_cmedia) != "undefined") {
                    if (_cmedia.nodeName == 'AUDIO') {
                        //console.info(_cmedia);
                        _cmedia.addEventListener('ended', handle_end);
                    } else {

                    }
                }


                //console.info("CLEAR THE TIMEOUT HERE")
                clearInterval(inter_checkReady);
                clearTimeout(inter_checkReady);
                setup_listeners();
                //console.info('setuped_listeners', cthis.hasClass('dzsap-loaded'), cthis)

                if (is_ie8()) {
                    cthis.addClass('lte-ie8')
                }

                setTimeout(function() {

                    //console.log(_currTime, )
                    if (_currTime && _currTime.outerWidth() > 0) {
                        currTime_outerWidth = _currTime.outerWidth();
                    }
                }, 1000);





                //===ie7 and ie8 does not have the indexOf property so let us add it
                if (is_ie8()) {
                    if (!Array.prototype.indexOf) {
                        Array.prototype.indexOf = function(elt) {
                            var len = this.length >>> 0;

                            var from = Number(arguments[1]) || 0;
                            from = (from < 0) ?
                                Math.ceil(from) :
                                Math.floor(from);
                            if (from < 0)
                                from += len;

                            for (; from < len; from++) {
                                if (from in this &&
                                    this[from] === elt)
                                    return from;
                            }
                            return -1;
                        };
                    }
                }

                //console.info('type - ',type);
                if (type != 'fake') {
                    if (o.settings_exclude_from_list != 'on' && dzsap_list && dzsap_list.indexOf(cthis) == -1) {
                        if (o.fakeplayer == null) {

                            dzsap_list.push(cthis);
                        }
                    }



                    if (o.type_audio_stop_buffer_on_unfocus == 'on') {


                        cthis.data('type_audio_stop_buffer_on_unfocus', 'on');

                        cthis.get(0).api_destroy_for_rebuffer = function() {

                            if (o.type == 'audio') {
                                playfrom = _cmedia.currentTime;
                            }
                            //console.log(playfrom);
                            destroy_media();

                            destroyed_for_rebuffer = true;
                        }

                    }
                }

                //console.info("CHECK TIME",cthis);


                if (o.design_skin == 'skin-wave') {
                    if (o.skinwave_enableSpectrum == 'on') {

                        //console.info(typeof AudioContext);

                        console.info("USED AUDIO CONTEXT");



                        // trying to use only one audio ctx per page.
                        if (window.dzsap_audio_ctx == null) {
                            if (typeof AudioContext !== 'undefined') {
                                audioCtx = new AudioContext();
                                window.dzsap_audio_ctx = audioCtx;
                            } else if (typeof webkitAudioContext !== 'undefined') {
                                audioCtx = new webkitAudioContext();
                                window.dzsap_audio_ctx = audioCtx;
                            } else {
                                audioCtx = null;
                            }

                        } else {

                            audioCtx = window.dzsap_audio_ctx;
                        }

                        //console.info(audioCtx);

                        if (audioCtx) {


                            // console.info('audioCtx - ', audioCtx);




                            //if(!)
                            if (typeof audioCtx.createJavaScriptNode != 'undefined') {
                                javascriptNode = audioCtx.createJavaScriptNode(2048, 1, 1);
                            }
                            if (typeof audioCtx.createScriptProcessor != 'undefined') {
                                javascriptNode = audioCtx.createScriptProcessor(4096, 1, 1);
                                //console.log(javascriptNode);
                            }


                            function generateFakeArray() {

                                console.info('generateFakeArray()');
                                var maxlen = 256;

                                var arr = [];

                                for (var it1 = 0; it1 < maxlen; it1++) {
                                    arr[it1] = (maxlen - it1) / 2 + Math.random() * 100;

                                }

                                return arr;
                            }

                            if (is_android()) {


                                analyser = audioCtx.createAnalyser();
                                analyser.smoothingTimeConstant = 0.3;
                                analyser.fftSize = 512;


                                //oscillator = audioCtx.createOscillator();
                                //oscillator.start(0);

                                // Set up a script node that sets output to white noise
                                var url = data_source;


                                javascriptNode.onaudioprocess = function(event) {
                                    //var output = event.outputBuffer.getChannelData(0);
                                    //for (i = 0; i < output.length; i++) {
                                    //    output[i] = Math.random() / 10;
                                    //}

                                    // -- android



                                    var array = new Uint8Array(analyser.frequencyBinCount);
                                    //console.info(analyser, analyser.getByteFrequencyData(array), new Uint8Array(analyser.frequencyBinCount));
                                    //console.log('Processing buffer', array);
                                    analyser.getByteFrequencyData(array);

                                    lastarray = array.slice();

                                    if (playing) {
                                        lastarray = generateFakeArray();
                                    }

                                    //console.info(playing, lastarray);


                                };

                                // Connect oscillator to script node and script node to destination
                                // (should output white noise)
                                //                                oscillator.connect(javascriptNode);


                                webaudiosource = audioCtx.createMediaElementSource(_cmedia);
                                webaudiosource.connect(analyser);
                                //console.log(webaudiosource);
                                analyser.connect(audioCtx.destination);


                                javascriptNode.connect(audioCtx.destination);



                                //console.info('ceva');
                            } else {
                                if (javascriptNode) {


                                    // setup a analyzer
                                    analyser = audioCtx.createAnalyser();
                                    analyser.smoothingTimeConstant = 0.3;
                                    //analyser.fftSize = 64;
                                    //analyser.fftSize = 256;
                                    analyser.fftSize = 512;

                                    //console.log(analyser);

                                    // create a buffer source node


                                    //Steps 3 and 4
                                    //console.log(data_source);
                                    //console.log('hmm');
                                    //if( is_ios()){
                                    //    //console.log('is_safari');
                                    //    loadSound(url);
                                    //    audioBuffer = 'placeholder';
                                    //    _conControls.css({
                                    //        'opacity':0.5
                                    //    });
                                    //}

                                    console.info('is_ios - ',is_ios());


                                    // console.info("HMM  analyser", data_source);


                                    //console.log('cevaal');

                                    //console.log(_cmedia, _cmedia.get(0))
                                    //console.info(is_chrome(), is_firefox());

                                    // -- && (is_chrome() || is_firefox() || is_safari() || is_ios())


                                    if (type == 'audio' ) {

                                        // console.info(_cmedia);
                                        // return;
                                        // _cmedia.crossOrigin = "anonymous";
                                        if(is_ios()){
                                            //webaudiosource = audioCtx.createMediaStreamSource(_cmedia);
                                        }else{

                                        }
                                        webaudiosource = audioCtx.createMediaElementSource(_cmedia);
                                        webaudiosource.connect(analyser)
                                        analyser.connect(audioCtx.destination);

                                        // console.info(audioCtx);

                                        //var node = audioCtx.createGain(4096, 2, 2);
                                        //node.connect(javascriptNode);

                                        javascriptNode.connect(audioCtx.destination);

                                        //console.log(_cmedia, analyser, audioCtx.destination);



                                    }
                                    //playSound();

                                    var stopaudioprocessfordebug = false;
                                    setTimeout(function() {
                                        stopaudioprocessfordebug = true;
                                    }, 3000);


                                }
                            }


                            if(is_ios() ){
                                var file = null;
                                var fr = new FileReader();


                                var getFileBlob = function (url, cb) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("GET", url);
                                    xhr.responseType = "blob";
                                    xhr.addEventListener('load', function() {
                                        cb(xhr.response);
                                    });
                                    xhr.send();
                                };

                                var blobToFile = function (blob, name) {
                                    blob.lastModifiedDate = new Date();
                                    blob.name = name;
                                    return blob;
                                };

                                var getFileObject = function(filePathOrUrl, cb) {
                                    getFileBlob(filePathOrUrl, function (blob) {
                                        cb(blobToFile(blob, ''));
                                    });
                                };

                                fr.onload = function(e){
                                    var fileResult = e.target.result;

                                    audioCtx.decodeAudioData(fileResult, function(buffer) {

                                        //console.warn('decode successful');
                                        //console.warn(fileResult);
                                        audioBuffer = buffer;
                                    }, function(e) {
                                    });
                                }

                                var aux =
                                    getFileObject(data_source, function (fileObject) {
                                        file = fileObject;
                                        fr.readAsArrayBuffer(file);
                                    });

                                audioBuffer = 'waiting';
                            }




                        }
                    }
                }

                //console.info(ajax_view_submitted);

                if (ajax_view_submitted == 'auto') {
                    setTimeout(function() {
                        if (ajax_view_submitted == 'auto') {
                            ajax_view_submitted = 'off';
                        }
                    }, 1000);
                }

                //console.info('---- ADDED LOADED BUT FROM WHERE', cthis);
                loaded = true;
                cthis.addClass('dzsap-loaded');

                //                console.info(playfrom);

                if(o.default_volume=='default'){
                    defaultVolume = 1;
                }

                if (isNaN(Number(o.default_volume)) == false) {
                    defaultVolume = Number(o.default_volume);
                } else {
                    if (o.default_volume == 'last') {


                        if (localStorage != null && the_player_id) {

                            //console.info(the_player_id);


                            if (localStorage.getItem('dzsap_last_volume_' + the_player_id)) {

                                defaultVolume = localStorage.getItem('dzsap_last_volume_' + the_player_id);
                            } else {

                                defaultVolume = 1;
                            }
                        } else {

                            defaultVolume = 1;
                        }
                    }
                }

                if (o.volume_from_gallery) {
                    defaultVolume = o.volume_from_gallery;
                }


                // console.info(pargs);
                set_volume(defaultVolume, {
                    call_from: "from_init_loaded"
                });

                if (isInt(playfrom)) {
                    seek_to(playfrom, {
                        call_from: 'from_playfrom'
                    });
                }
                if (playfrom == 'last') {
                    if (typeof Storage != 'undefined') {
                        setTimeout(function() {
                            playfrom_ready = true;
                        })


                        if (typeof localStorage['dzsap_' + the_player_id + '_lastpos'] != 'undefined') {
                            seek_to(localStorage['dzsap_' + the_player_id + '_lastpos'], {
                                'call_from': 'last_pos'
                            });
                        }
                    }
                }

                //                console.info(cthis, o.autoplay);

                //console.info(margs);
                if (margs.do_not_autoplay != true) {

                    if (is_ie8() == false && o.autoplay == 'on') {
                        play_media({
                            'call_from':'do not autoplay not true'
                        });
                    };
                }

                if(_cmedia && _cmedia.duration){
                    cthis.addClass('meta-loaded');
                }


                check_time();

                setTimeout(function() {
                    //console.info(cthis.find('.wave-download'));
                    cthis.find('.wave-download').bind('click', handle_mouse);
                }, 500);


            }


            function isInt(n) {
                return typeof n == 'number' && Math.round(n) % 1 == 0;
            }

            function isValid(n) {
                return typeof n != 'undefined' && n != '';
            }

            function handle_mouse(e) {
                var _t = $(this);
                if (e.type == 'click') {
                    if (_t.hasClass('wave-download')) {
                        ajax_submit_download();
                    }
                    if (_t.hasClass('prev-btn')) {
                        click_prev_btn();
                    }
                    if (_t.hasClass('next-btn')) {
                        click_next_btn();
                    }
                    if (_t.hasClass('dzsap-repeat-button')) {

                        // console.info("REPEAT");
                        if(playing){
                        }
                        seek_to(0, {
                            call_from:"repeat"
                        });
                    }
                    if (_t.hasClass('dzsap-loop-button')) {

                        if(_t.hasClass('active')){
                            _t.removeClass('active');
                            loop_active = false;
                        }else{

                            _t.addClass('active');
                            loop_active = true;

                        }
                        console.info('loop_active - ',loop_active, cthis);


                    }
                }
                if (e.type == 'mouseover') {
                    console.log('mouseover');
                }
                if (e.type == 'mouseenter') {
                    // console.log('mouseenter');

                    if(o.preview_on_hover=='on'){
                        seek_to_perc(0);

                        play_media({
                            'call_from':'preview_on_hover'
                        });
                    }
                }
                if (e.type == 'mouseleave') {
                    // console.log('mouseleave');


                    if(o.preview_on_hover=='on'){
                        seek_to_perc(0);

                        pause_media();
                    }
                }
            }

            function mouse_starrating(e) {
                var _t = $(this);


                if (cthis.has(_t).length == 0) {
                    return;
                }

                if (e.type == 'mouseout' || e.type == 'mouseleave') {
                    cthis.find('.star-rating-prog-clip').css({
                        'width': 0
                    })


                    cthis.find('.star-rating-set-clip').css({
                        'opacity': 1
                    })


                }
                if (e.type == 'mousemove') {
                    //console.log(_t);
                    var mx = e.pageX - _t.offset().left;
                    var my = e.pageX - _t.offset().left;

                    //console.info(Math.round(mx/ (_t.outerWidth()/5)) );


                    if (starrating_alreadyrated > -1) {
                        starrating_index = starrating_alreadyrated;
                    } else {
                        starrating_index = mx / (_t.outerWidth() / 5);
                    }



                    if (starrating_index > 4) {
                        starrating_index = 5;
                    } else {
                        starrating_index = Math.round(starrating_index);
                    }

                    //                    console.info(starrating_index, cthis.find('.star-rating-prog-clip'));
                    cthis.find('.star-rating-prog-clip').css({
                        'width': _t.outerWidth() / 5 * starrating_index
                    })



                    cthis.find('.star-rating-set-clip').css({
                        'opacity': 0
                    })
                }
                if (e.type == 'click') {


                    if (starrating_alreadyrated > -1) {
                        return;
                    }

                    ajax_submit_rating(starrating_index);
                }


            }



            function onError() {

            }

            function drawSpectrum(argarray) {
                //console.info(array);
                //console.info()
                //console.log($('.scrub-bg-canvas').eq(0).get(0).width, canw);

                //console.log(_scrubBgCanvas.get(0).width, _scrubBgCanvas.width())


                // console.info(_scrubbarbg_canvas);
                if(_scrubbarbg_canvas){

                    draw_canvas(_scrubbarbg_canvas.get(0), argarray, o.design_wave_color_bg);
                    // draw_canvas(_scrubbarprog_canvas.get(0), argarray, o.design_wave_color_progress);
                }

                return false;



            };




            // log if an error occurs
            function onError(e) {
                console.log(e);
            }

            function click_prev_btn() {

                if (o.parentgallery && typeof(o.parentgallery.get(0)) != "undefined") {
                    o.parentgallery.get(0).api_goto_prev();
                }
            }

            function click_next_btn() {
                if (o.parentgallery && typeof(o.parentgallery.get(0)) != "undefined") {
                    o.parentgallery.get(0).api_goto_next();
                }
            }

            function check_time(pargs) {


                // -- enter frame
                //console.info('check_time()');

                var margs = {
                    'fire_only_once': false
                }

                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                // console.info('check_time()', cthis);

                if (cthis.attr('id') == 'apminimal') {

                    //console.log(cthis,'check');
                }
                if (destroyed) {
                    return false;
                }

                if (debug_var) {
                    //console.info('check_time()' , cthis);
                    // debug_var = false;
                }


                if (sw_suspend_enter_frame) {
                    return false;
                }


                if (type == 'youtube') {
                    if (_cmedia && _cmedia.getDuration) {
                        real_time_total = _cmedia.getDuration();
                        real_time_curr = _cmedia.getCurrentTime();
                    }



                    if (playfrom == 'last' && playfrom_ready) {
                        if (typeof Storage != 'undefined') {
                            localStorage['dzsap_' + the_player_id + '_lastpos'] = time_curr;
                        }
                    }
                }
                if (type == 'audio' || ( type=='fake' && o.fakeplayer) ) {
                    if (is_flashplayer == true) {
                        if (o.settings_backup_type == 'light') {
                            if (str_ie8 == '' && typeof(_cmedia) != "undefined") {

                                eval("if(typeof _cmedia.fn_getsoundduration" + cthisId + " != 'undefined'){time_total = parseFloat(_cmedia.fn_getsoundduration" + cthisId + "())};");
                                eval("if(typeof _cmedia.fn_getsoundcurrtime" + cthisId + " != 'undefined'){time_curr = parseFloat(_cmedia.fn_getsoundcurrtime" + cthisId + "())};");
                            }
                        }


                        //console.log(_cmedia.fn_getSoundCurrTime());
                    } else {
                        if (o.type != 'shoutcast') {


                            if (audioBuffer && audioBuffer != 'placeholder' && audioBuffer != 'waiting') {
                                //                                console.info(time_curr);

                                real_time_total = audioBuffer.duration;
                                real_time_curr = audioCtx.currentTime;
                                //                                console.log(audioBuffer, audioBuffer.currentTime, audioBuffer.duration);

                            }else{
                                if (_cmedia) {
                                    real_time_total = _cmedia.duration;

                                    if(_cmedia.duration){
                                        // cthis.addClass('meta-loaded');
                                    }

                                    if (inter_audiobuffer_workaround_id == 0) {

                                        real_time_curr = _cmedia.currentTime;
                                    }
                                }

                            }



                            //                            console.info(time_curr, time_total, inter_audiobuffer_workaround_id);
                            //                            console.info(audioBuffer, audioCtx, webaudiosource);

                            if(debug_var){
                                //console.info(audioBuffer);
                                //console.info(audioCtx);
                                //console.info(time_curr,time_total);
                                //debug_var = false;
                            }

                            if (audioCtx && is_firefox()) {
                                //                                time_curr = audioCtx.currentTime;
                            }

                            if (playfrom == 'last' && playfrom_ready) {
                                if (typeof Storage != 'undefined') {
                                    localStorage['dzsap_' + the_player_id + '_lastpos'] = time_curr;
                                    //                                    console.info(localStorage['dzsap_'+the_player_id+'_lastpos']);
                                }
                            }


                            if (o.design_skin == 'skin-wave') {
                                if (o.skinwave_comments_displayontime == 'on') {

                                    var timer_curr_perc = Math.round((real_time_curr / real_time_total) * 100) / 100;

                                    if(type=='fake'){
                                        timer_curr_perc = Math.round((time_curr / time_total) * 100) / 100;
                                    }

                                    //                                    console.info(timer_curr_perc);

                                    if (_commentsHolder) {

                                        _commentsHolder.children().each(function() {
                                            var _t = $(this);
                                            if (_t.hasClass('dzstooltip-con')) {
                                                var aux = Math.round((parseFloat(_t.css('left')) / _commentsHolder.outerWidth()) * 100) / 100;

                                                if(cthis.attr('id')=='track5'){
                                                    // console.info('hmm');
                                                    // console.info(parseFloat(_t.css('left')), aux, time_curr, timer_curr_perc, real_time_curr,Math.abs(aux - real_time_curr));
                                                }



                                                if (aux) {

                                                    if (Math.abs(aux - timer_curr_perc) < 0.02) {
                                                        _commentsHolder.find('.dzstooltip').removeClass('active');
                                                        _t.find('.dzstooltip').addClass('active');
                                                    } else {
                                                        _t.find('.dzstooltip').removeClass('active');
                                                    }
                                                }
                                            }
                                        })
                                    }
                                }
                            }
                        }

                    }
                }


                if (cthis.hasClass('first-played') == false) {

                    if (!(cthis.attr('data-playfrom')) || cthis.attr('data-playfrom') == '0') {
                        real_time_curr = 0;
                        if ($(_cmedia) && $(_cmedia).html() && $(_cmedia).html().indexOf('api.soundcloud.com') > -1) {
                            if(_cmedia.currentTime!=0){

                                seek_to(0, {
                                    'call_from': 'first_played_false'
                                });
                            }
                        }
                    }


                    // console.info(_cmedia, cthis, $(_cmedia).html());
                }

                //if(cthis.hasClass("skin-minimal")){ console.log(time_curr, time_total) };

                //                console.info(time_curr, time_total, sw);

                //console.info(time_curr,type);
                if (type == 'fake' || o.fakeplayer) {


                    // console.info(time_curr,_cmedia.currentTime,_cmedia);


                    if (time_total == 0) {

                        //if(cthis.attr('id')=='ap26'){
                        //    console.info(time_total, _cmedia, _cmedia.duration);
                        //}
                        if (_cmedia) {
                            time_total = _cmedia.duration;
                            if (inter_audiobuffer_workaround_id == 0) {

                                time_curr = _cmedia.currentTime;
                            }
                        }
                    }
                    if (time_curr == 5) {
                        time_curr = 0;
                    }


                    // console.info(time_curr);
                    // -- trying to fix some soundcloud wrong reporting





                    // console.info(time_curr,cthis.hasClass('first-played'), cthis.attr('data-playfrom'), cthis)
                    real_time_curr = time_curr;
                    real_time_total = time_total;
                }

                time_curr = real_time_curr;
                time_total = real_time_total;

                if (sample_time_start > 0) {
                    time_curr = sample_time_start + real_time_curr;
                }
                if (sample_time_total > 0) {
                    time_total = sample_time_total;
                }

                if (cthis.hasClass('is-playing')) {

                    //console.info(sw);
                }
                //--- incase of new skin - watch sw it will be 0
                spos = (time_curr / time_total) * sw;
                if (isNaN(spos)) {
                    spos = 0;
                }
                if (spos > sw) {
                    spos = sw;
                }

                //console.info(time_curr, time_total, sw);

                //console.log(_scrubbar, _scrubbar.children('.scrub-prog'), spos, time_total, '-timecurr ', time_curr, sw);


                //                console.info(audioBuffer);


                if (audioBuffer == null) {
                    //console.info(spos, _scrubbar.width());
                    _scrubbar.children('.scrub-prog').css({
                        'width': spos
                    })
                    if (o.skinwave_enableReflect == 'on') {
                        _scrubbar.children('.scrub-prog-reflect').css({
                            'width': spos
                        })
                    }
                }

                if (debug_var) {

                    //console.info(cthis, _feed_fakePlayer, time_curr/time_total);
                    //debug_var = false;
                }


                //console.log(cthis, _feed_fakePlayer);

                if (_feed_fakePlayer) {
                    //console.log(cthis, _feed_fakePlayer);

                    if(_feed_fakePlayer.get(0)){
                        if(_feed_fakePlayer.get(0).api_get_time_curr){
                            // console.warn('getting time total', _feed_fakePlayer.get(0).api_get_time_total());
                            if(isNaN(_feed_fakePlayer.get(0).api_get_time_total()) || _feed_fakePlayer.get(0).api_get_time_total()=='' || _feed_fakePlayer.get(0).api_get_time_total()==0){
                                // console.warn('setting time total');
                                _feed_fakePlayer.get(0).api_set_time_total(time_total);
                            }
                        }
                    }

                    if(_feed_fakePlayer.get(0) && _feed_fakePlayer.get(0).api_seek_to_onlyvisual){
                        _feed_fakePlayer.get(0).api_seek_to_onlyvisual(time_curr / time_total);
                    }else{
                        console.warn('hmm');
                    }

                }

                if (o.design_skin == 'skin-pro') {
                    //                    console.info(spos,sw,spos/sw,Math.easeOutQuart(spos/sw, 0, sw,1));

                    // var spos_eased = parseInt(Math.easeOutQuad(spos/sw, 0, sw,1), 10);
                    // // var spos_eased = parseInt(Math.easeOutQuad(spos/sw, 0, sw,1), 10);
                    // //
                    // _scrubbar.children('.scrub-prog').css({
                    //     'width' : spos_eased
                    // })
                }



                if (cthis.hasClass('skin-minimal')) {
                    //console.log(skin_minimal_canvasplay);
                    //alert(can_canvas());

                    if (is_ie8() || !can_canvas() || is_opera()) {
                        _conPlayPause.addClass('canvas-fallback');
                    } else {
                        var ctx = skin_minimal_canvasplay.getContext('2d');
                        //console.log(ctx);


                        var ctx_w = skin_minimal_canvasplay.width;
                        var ctx_h = skin_minimal_canvasplay.height;

                        // console.info(ctx_w);
                        var pw = ctx_w / 100;
                        var ph = ctx_h / 100;
                        spos = Math.PI * 2 * (time_curr / time_total);
                        if (isNaN(spos)) {
                            spos = 0;
                        }
                        if (spos > Math.PI * 2) {
                            spos = Math.PI * 2;
                        }

                        ctx.clearRect(0, 0, ctx_w, ctx_h);
                        //console.log(ctx_w, ctx_h);




                        var gradient = ctx.createLinearGradient(0, 0, 0, ctx_h);


                        var aux1 = parseInt(o.design_wave_color_progress, 16);


                        var color1 = aux1;
                        var color2 = aux1 + 40;

                        //console.info(aux1.toString(16));


                        gradient.addColorStop("0", "#" + color1.toString(16));
                        gradient.addColorStop("1.0", "#" + color2.toString(16));


                        ctx.beginPath();
                        ctx.arc((50 * pw), (50 * ph), (40 * pw), 0, Math.PI * 2, false);
                        ctx.fillStyle = "rgba(0,0,0,0.1)";
                        ctx.fill();



                        ctx.beginPath();
                        ctx.arc((50 * pw), (50 * ph), (30 * pw), 0, Math.PI * 2, false);
                        //ctx.moveTo(110,75);
                        ctx.fillStyle = gradient;

                        ctx.fill();

                        //console.log(spos);
                        ctx.beginPath();
                        ctx.arc((50 * pw), (50 * ph), (34 * pw), 0, spos, false);
                        //ctx.fillStyle = "rgba(0,0,0,0.3)";
                        ctx.lineWidth = (10 * pw);
                        ctx.strokeStyle = 'rgba(0,0,0,0.3)';
                        ctx.stroke();



                        ctx.beginPath();
                        ctx.strokeStyle = "red";

                        //==draw the triangle
                        ctx.moveTo((44 * pw), (40 * pw));
                        ctx.lineTo((57 * pw), (50 * pw));
                        ctx.lineTo((44 * pw), (60 * pw));
                        ctx.lineTo((44 * pw), (40 * pw));
                        ctx.fillStyle = "#ddd";
                        ctx.fill();


                        ctx = skin_minimal_canvaspause.getContext('2d');
                        //console.log(ctx);

                        ctx_w = $(skin_minimal_canvaspause).width();
                        ctx_h = $(skin_minimal_canvaspause).height();
                        pw = ctx_w / 100;
                        ph = ctx_h / 100;

                        ctx.clearRect(0, 0, ctx_w, ctx_h);
                        //console.log(ctx_w, ctx_h);

                        //console.log((time_curr / time_total));

                        ctx.beginPath();
                        ctx.arc((50 * pw), (50 * ph), (40 * pw), 0, Math.PI * 2, false);
                        ctx.fillStyle = "rgba(0,0,0,0.1)";
                        ctx.fill();



                        ctx.beginPath();
                        ctx.arc((50 * pw), (50 * ph), (30 * pw), 0, Math.PI * 2, false);
                        //ctx.moveTo(110,75);
                        ctx.fillStyle = gradient;

                        ctx.fill();

                        //console.log(spos);
                        ctx.beginPath();
                        ctx.arc((50 * pw), (50 * ph), (34 * pw), 0, spos, false);
                        //ctx.fillStyle = "rgba(0,0,0,0.3)";
                        ctx.lineWidth = (10 * pw);
                        ctx.strokeStyle = 'rgba(0,0,0,0.35)';
                        ctx.stroke();

                        ctx.fillStyle = "#ddd";
                        ctx.fillRect((43 * pw), (40 * pw), (6 * pw), (20 * pw));
                        ctx.fillRect((53 * pw), (40 * pw), (6 * pw), (20 * pw));
                    }
                    //console.log('ceva');
                }


                //                console.info(o.design_skin);

                // -- enter_frame
                if (o.design_skin == 'skin-wave') {
                    if (o.skinwave_enableSpectrum == 'on') {
                        //console.info(_scrubBgCanvas.width());



                        if (debug_var) {

                            // console.groupCollapsed("debug analyzer data");
                            // console.info(lastarray);
                            // console.info(last_lastarray);
                            // console.groupEnd();

                            // if(lastarray){
                            //     for(var i3=0;i3<lastarray.length;i3++){
                            //         var change_vy = array[i3] - lastarray[i3];
                            //         array[i3] = Math.easeOutQuad(1, lastarray[i], change_vy,20);
                            //     }
                            // }

                            //debug_var = false;
                        }

                        // -- easing


                        if(playing){

                        }else{
                            requestAnimFrame(check_time);
                            return false;
                        }


                        /*
                         ctx.imageSmoothingEnabled = false;
                         ctx.imageSmoothing = false;
                         ctx.imageSmoothingQuality = "high";
                         ctx.webkitImageSmoothing = false;
                         */

                        var cwidth = 0,
                            cheight = 0,
                            meterWidth = 10, //width of the meters in the spectrum
                            gap = 2, //gap between meters
                            capHeight = 2,
                            animationId = 2,
                            capStyle = '#fff',
                            meterNum = canw / (10 + 2), //count of the meters
                            capYPositionArray = []


                        //console.info(_scrubBgCanvas);
                        if(_scrubBgCanvas){

                            canw = _scrubBgCanvas.width();
                            canh = _scrubBgCanvas.height();

                            _scrubBgCanvas.get(0).width = canw;
                            _scrubBgCanvas.get(0).height = canh;
                        }



                        var drawMeter = function() {
                            //var array = new Uint8Array(analyser.frequencyBinCount);
                            //analyser.getByteFrequencyData(array);


                            lastarray = new Uint8Array(analyser.frequencyBinCount);
                            //console.info(analyser, analyser.getByteFrequencyData(array), new Uint8Array(analyser.frequencyBinCount));
                            //console.info(array);
                            analyser.getByteFrequencyData(lastarray);

                            if(debug_var){

                                console.info(analyser.frequencyBinCount);
                                console.info(lastarray);
                                debug_var = false;
                            }


                            if(lastarray && lastarray.length){




                                //fix when some sounds end the value still not back to zero
                                var len = lastarray.length;
                                for (var i = len - 1; i >= 0; i--) {
                                    //lastarray[i] = 0;

                                    if(i<len/2){

                                        lastarray[i] = lastarray[i]/255 * canh;
                                    }else{

                                        lastarray[i] = lastarray[len - i]/255 * canh;
                                    }
                                };



                                if(last_lastarray){
                                    for(var i3=0;i3<last_lastarray.length;i3++){
                                        begin_viy = last_lastarray[i3]; // -- last value
                                        change_viy = lastarray[i3] - begin_viy; // -- target value - last value
                                        duration_viy = 3;
                                        lastarray[i3] = Math.easeIn(1, begin_viy, change_viy,duration_viy);
                                    }
                                }
                                // -- easing END


                                // last_lastarray = [];
                                // last_lastarray = last_lastarray.concat(lastarray);




                                //allCapsReachBottom = true;
                                //for (var i = capYPositionArray.length - 1; i >= 0; i--) {
                                //    allCapsReachBottom = allCapsReachBottom && (capYPositionArray[i] === 0);
                                //};
                                //
                                //
                                //console.info('allCapsReachBottom - ',allCapsReachBottom);
                                //
                                //if (allCapsReachBottom) {
                                //    cancelAnimationFrame(animationId); //since the sound is top and animation finished, stop the requestAnimation to prevent potential memory leak,THIS IS VERY IMPORTANT!
                                //    return;
                                //};

                                //console.info(gradient);


                                var step = Math.round(lastarray.length / meterNum); //sample limited data from the total array





                                draw_canvas(_scrubBgCanvas.get(0),lastarray , ''+o.design_wave_color_bg,{
                                    'call_from':'spectrum'


                                })



                                if(lastarray){

                                    last_lastarray = lastarray.slice();
                                }

                                /*
                                 if(debug_var){

                                 //console.info(lastarray);
                                 //console.info(lastarray.length);
                                 //console.info(meterWidth);
                                 console.info('meterNum - ',meterNum);
                                 //console.info('capHeight - ',capHeight);
                                 //console.info(canw, canh, meterWidth, capHeight,meterNum, step);
                                 //console.info(1 * 12  , canh - 100 + capHeight, meterWidth, canh);
                                 }
                                 _scrubBgCanvasCtx.clearRect(0, 0, canw, canh);
                                 for (var i = 0; i < meterNum; i++) {
                                 var value = lastarray[i * step];
                                 if (capYPositionArray.length < Math.round(meterNum)) {
                                 capYPositionArray.push(value);
                                 };
                                 _scrubBgCanvasCtx.fillStyle = capStyle;

                                 //console.info(gradient);
                                 _scrubBgCanvasCtx.fillStyle = ggradient; // -- set the filllStyle to gradient for a better look


                                 if(isNaN(value)){
                                 //value = lastarray[Math.round(Math.random()*20)];
                                 }
                                 if(debug_var){
                                 //console.warn('canh - ',canh);
                                 console.warn('step - ',i * step);
                                 console.warn('value - ',value);

                                 console.warn(i * 12  , canh - value + capHeight, meterWidth, canh);
                                 }

                                 //console.info(i * 12  , canh - value + capHeight, meterWidth, canh)
                                 //console.info(i, cheight, value, capHeight, meterWidth, cheight);
                                 _scrubBgCanvasCtx.fillRect(i * 12  , canh - value + capHeight, meterWidth, canh); //the meter
                                 }

                                 */
                                //_scrubBgCanvasCtx.clearRect(0, 0, canw, canh);
                            }

                        }

                        drawMeter();


                        /*

                         if(last_lastarray){
                         for(var i3=0;i3<last_lastarray.length;i3++){
                         begin_viy = last_lastarray[i3]; // -- last value
                         change_viy = lastarray[i3] - begin_viy; // -- target value - last value
                         duration_viy = 3;
                         lastarray[i3] = Math.easeIn(1, begin_viy, change_viy,duration_viy);
                         }
                         }
                         // -- easing END


                         if (lastarray) {
                         drawSpectrum(lastarray);
                         }

                         // last_lastarray = [];
                         // last_lastarray = last_lastarray.concat(lastarray);

                         if(lastarray){

                         last_lastarray = lastarray.slice();
                         }

                         */

                    }

                    if (_currTime) {
                        //                        console.info(_currTime, time_curr, time_total, formatTime(time_curr))

                        if (o.skinwave_timer_static != 'on') {

                            _currTime.css({
                                'left': spos
                            });
                            if (spos > sw - currTime_outerWidth) {
                                //console.info(sw, currTime_outerWidth);
                                _currTime.css({
                                    'left': sw - currTime_outerWidth
                                })
                            }
                            if (spos > sw - 30) {
                                _totalTime.css({
                                    'opacity': 1 - (((spos - (sw - 30)) / 30))
                                });
                            } else {
                                if (_totalTime.css('opacity') != '1') {
                                    _totalTime.css({
                                        'opacity': ''
                                    });
                                }
                            };
                        };
                    }
                }
                if (_currTime) {
                    //console.info(_currTime, time_curr, formatTime(time_curr))
                    //console.info("CEVA");
                    _currTime.html(formatTime(time_curr));
                    _totalTime.html(formatTime(time_total));
                }

                //                console.log(time_curr, time_total);
                if (time_total > 0 && time_curr >= time_total - 0.07) {
                    handle_end();
                }



                // -- debug check_time
                // inter_check = setTimeout(check_time, 2000);
                if (margs.fire_only_once != true) {
                    if (is_flashplayer == true || type == 'youtube') {
                        inter_check = setTimeout(check_time, 500);
                    } else {
                        requestAnimFrame(check_time);
                        // inter_check = setTimeout(check_time, 1000); // -- animframe for debug
                    }
                }



            }

            function click_playpause(e) {
                // console.log('click_playpause', 'playing - ',playing);
                var _t = $(this);
                //console.log(_t);


                if(cthis.hasClass('listeners-setuped')){

                }else{

                    $(_cmedia).attr('preload','auto');

                    setup_listeners();
                    init_loaded();

                    var it3 = setInterval(function(){

                        // console.info(_cmedia, _cmedia.duration);
                        if(_cmedia && _cmedia.duration && isNaN(_cmedia.duration) == false){

                            real_time_total = _cmedia.duration;
                            time_total = real_time_total;


                            cthis.addClass('meta-loaded');
                            // console.warn(_totalTime);
                            if (_totalTime) {
                                _totalTime.html(formatTime(time_total));
                            }

                            clearInterval(it3);
                        }
                    },1000);
                }


                if (o.design_skin == 'skin-minimal') {

                    var center_x = _t.offset().left + skin_minimal_button_size/2;
                    var center_y = _t.offset().top + skin_minimal_button_size/2;
                    var mouse_x = e.pageX;
                    var mouse_y = e.pageY;
                    var pzero_x = center_x + skin_minimal_button_size/2;
                    var pzero_y = center_y;

                    //var angle = Math.acos(mouse_x - center_x);

                    //console.log(pzero_x, pzero_y, mouse_x, mouse_y, center_x, center_y, mouse_x - center_x, angle);

                    //A = center, B = mousex, C=pointzero

                    var AB = Math.sqrt(Math.pow((mouse_x - center_x), 2) + Math.pow((mouse_y - center_y), 2));
                    var AC = Math.sqrt(Math.pow((pzero_x - center_x), 2) + Math.pow((pzero_y - center_y), 2));
                    var BC = Math.sqrt(Math.pow((pzero_x - mouse_x), 2) + Math.pow((pzero_y - mouse_y), 2));


                    var angle = Math.acos((AB + AC + BC) / (2 * AC * AB));
                    var angle2 = Math.acos((mouse_x - center_x) / (skin_minimal_button_size/2));

                    //console.info(AB, AC, BC, angle, (mouse_x - center_x), angle2, Math.PI);

                    var perc = -(mouse_x - center_x - (skin_minimal_button_size/2)) * 0.005; //angle2 / Math.PI / 2;


                    if (mouse_y < center_y) {
                        perc = 0.5 + (0.5 - perc)
                    }

                    if (!(is_flashplayer == true && is_firefox()) && AB > 20) {
                        seek_to_perc(perc, {
                            call_from: 'click_playpause'
                        });
                        return;
                    }
                }



                //unghi = acos (x - centruX) = asin(centruY - y)


                if (playing == false) {
                    play_media({
                        'call_from':'click_playpause'
                    });
                } else {
                    pause_media();
                }

            }

            function handle_end() {
                //console.log('end');
                seek_to(0, {
                    'call_from': 'handle_end'
                });

                // console.info('handle_end', cthis,    o.fakeplayer, 'action_audio_end - ',action_audio_end);


                if(o.fakeplayer){
                    // -- lets leave fake player handle handle_end
                    return false;
                }
                // console.info('loop_active ( fromdzspend) - ',loop_active, cthis);

                if (o.loop == 'on' || loop_active) {
                    play_media({
                        'call_from':'track_end'
                    });
                    return false;
                } else {
                    pause_media();
                }

                if (o.parentgallery && typeof(o.parentgallery) != 'undefined') {
                    //console.log(o.parentgallery);
                    o.parentgallery.get(0).api_handle_end();
                }







                setTimeout(function() {
                    if (cthis.hasClass('is-single-player')) {
                        if (dzsap_list_for_sync_players.length > 0) {
                            for (var i24 in dzsap_list_for_sync_players) {
                                if (dzsap_list_for_sync_players[i24].get(0) == cthis.get(0)) {
                                    // console.info('THIS IS ',i24,dzsap_list_for_sync_players.length-1);

                                    if (i24 < dzsap_list_for_sync_players.length - 1) {
                                        i24 = parseInt(i24, 10);
                                        var _c_ = dzsap_list_for_sync_players[i24 + 1].get(0);

                                        // console.info(_c_, i24, dzsap_list_for_sync_players[i24+1]);
                                        if (_c_ && _c_.api_play_media) {
                                            setTimeout(function() {
                                                _c_.api_play_media({
                                                    'call_from':'api_pause_other_players'
                                                });
                                            }, 200);

                                        }
                                    }
                                }
                            }
                        }
                    }
                }, 100);

                setTimeout(function() {
                    if (action_audio_end) {
                        action_audio_end(cthis);
                    }
                }, 200);

            }

            Math.easeOutQuart = function(t, b, c, d) {
                t /= d;
                t--;
                return -c * (t * t * t * t - 1) + b;
            };
            Math.easeOutQuad = function(t, b, c, d) {
                return -c * t / d * (t / d - 2) + b;
            };
            Math.easeIn = function(t, b, c, d) {
                // console.info('math.easein')

                return -c *(t/=d)*(t-2) + b;

            };
            Math.easeOutQuad = function(t, b, c, d) {
                return -c * t / d * (t / d - 2) + b;
            };
            Math.easeOutQuad_rev = function(t, b, c, d) {
                // t /= d;
                return (c * d + d * Math.sqrt(c * (c + b - t))) / c;
            };


            function handleResize(e, pargs) {



                var margs = {

                    'call_from' : 'default'
                }

                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                //console.info('handleResize', margs);

                //cthis.attr('data-pcm')
                ww = $(window).width();
                tw = cthis.width();
                th = cthis.height();


                if (_scrubBgCanvas && typeof(_scrubBgCanvas.width)=='function') {
                    canw = _scrubBgCanvas.width();
                    canh = _scrubBgCanvas.height();

                }

                // console.info('handleResize', _commentsHolder)

                if (tw <= 720) {
                    cthis.addClass('under-720');
                } else {

                    cthis.removeClass('under-720');
                }
                if (tw <= 500) {
                    cthis.addClass('under-500');
                } else {

                    cthis.removeClass('under-500');
                }


                sw = tw;
                if (o.design_skin == 'skin-default') {
                    sw = tw;
                }
                if (o.design_skin == 'skin-pro') {
                    sw = tw;
                }
                if (o.design_skin == 'skin-silver') {
                    sw = tw;

                    sw = _scrubbar.width();
                    //console.info(sw);




                    if (o.scrubbar_tweak_overflow_hidden == 'on') {
                        sw = tw - _apControlsLeft.width() - _apControlsRight.outerWidth() - 15;

                        // -- TBC
                        _scrubbar.css({
                            'left': _apControlsLeft.width(),
                            'width': sw
                        });

                        if(o.skinwave_wave_mode=='canvas'){
                            _scrubbar.find('.scrub-prog-img').width(sw);
                        }
                    }

                }

                if (o.design_skin == 'skin-justthumbandbutton') {
                    tw = cthis.children('.audioplayer-inner').outerWidth();
                    sw = tw;
                }
                if (o.design_skin == 'skin-redlights' || o.design_skin == 'skin-steel') {
                    sw = _scrubbar.width();
                }


                //console.info(sw);




                if (o.design_skin == 'skin-wave') {
                    sw = _scrubbar.outerWidth(false);
                    // console.info('scrubbar width - ', sw, _scrubbar);

                    scrubbar_h = _scrubbar.outerHeight(false);

                    if (_commentsHolder) {

                        var aux = _scrubbar.offset().left - cthis.offset().left;

                        //console.log(aux);

                        _commentsHolder.css({
                            'width': sw,
                            'left': aux + 'px'
                        })
                        //return;
                        _commentsHolder.addClass('active');

                        //                        _commentsHolder.find('.a-comment').each(function(){
                        //                            var _t = $(this);
                        //
                        //
                        ////                            console.info(_t, _t.offset(), _t.find('.dzstooltip').eq(0).width(), _t.offset().left + _t.find('.dzstooltip').eq(0).width(), _t.offset().left + _t.find('.dzstooltip').eq(0).width() > ww - 50)
                        //                            if(_t.offset().left + _t.find('.dzstooltip').eq(0).width() > ww - 50){
                        //                                _t.find('.dzstooltip').eq(0).addClass('align-right');
                        //                            }else{
                        //
                        //                                _t.find('.dzstooltip').eq(0).removeClass('align-right');
                        //                            }
                        //                        })
                    }

                    if (cthis.hasClass('fullflashbackup')) {
                        if (_commentsHolder) {
                            _commentsHolder.css({
                                'width': tw - 212,
                                'left': 212
                            })
                            if (tw <= 480) {
                                _commentsHolder.css({
                                    'width': tw - 112,
                                    'left': 112
                                })
                            }
                            _commentsHolder.addClass('active');
                        }

                    }
                }

                //console.info(o.design_skin, tw, sw);


                if (res_thumbh == true) {

                    //                    console.info(cthis.get(0).style.height);


                    if (o.design_skin == 'skin-default') {


                        if (cthis.get(0) != undefined) {
                            // if the height is auto then
                            if (cthis.get(0).style.height == 'auto') {
                                cthis.height(200);
                            }
                        }

                        var cthis_height = _audioplayerInner.height();
                        if (typeof cthis.attr('data-initheight') == 'undefined' && cthis.attr('data-initheight') != '') {
                            cthis.attr('data-initheight', _audioplayerInner.height());
                        } else {
                            cthis_height = Number(cthis.attr('data-initheight'));
                        }

                        console.info('cthis_height - ', cthis_height, cthis.attr('data-initheight'));

                        if (o.design_thumbh == 'default') {

                            design_thumbh = cthis_height - 44;
                        }

                    }

                    _audioplayerInner.find('.the-thumb').eq(0).css({
                        // 'height': design_thumbh
                    })
                }


                //===display none + overflow hidden hack does not work .. yeah
                //console.log(cthis, _scrubbar.children('.scrub-bg').width());

                if (cthis.css('display') != 'none') {
                    _scrubbar.find('.scrub-bg-img').eq(0).css({
                        // 'width' : _scrubbar.children('.scrub-bg').width()
                    });
                    _scrubbar.find('.scrub-prog-img').eq(0).css({
                        'width': _scrubbar.children('.scrub-bg').width()
                    });
                    _scrubbar.find('.scrub-prog-canvas').eq(0).css({
                        'width': _scrubbar.children('.scrub-bg').width()
                    });
                    _scrubbar.find('.scrub-prog-img-reflect').eq(0).css({
                        'width': _scrubbar.children('.scrub-bg').width()
                    });
                    _scrubbar.find('.scrub-prog-canvas-reflect').eq(0).css({
                        'width': _scrubbar.children('.scrub-bg').width()
                    });
                }



                cthis.removeClass('under-240 under-400');
                if (tw <= 240) {
                    cthis.addClass('under-240');
                }
                if (tw <= 400) {
                    cthis.addClass('under-400');
                    if (_controlsVolume) {
                    }

                } else {

                }





                //console.info(_conPlayPause.outerWidth(), o.design_skin);

                var aux2 = 50;

                // ---skin-wave
                if (o.design_skin == 'skin-wave') {
                    //console.info((o.design_thumbw + 20));
                    controls_left_pos = 0;
                    if (cthis.find('.the-thumb').length > 0) {
                        controls_left_pos += cthis.find('.the-thumb').width() + 20;
                    }

                    controls_left_pos += 70;

                    var sh = _scrubbar.eq(0).height();


                    if (is_flashplayer && o.settings_backup_type == 'full') {
                        sh = 140;
                        controls_left_pos = 280;

                        if (tw <= 480) {
                            controls_left_pos = 180;
                        }
                    }

                    if (o.skinwave_mode == 'small') {
                        controls_left_pos -= 80;
                        sh = 5;

                        controls_left_pos += 13;
                        _conPlayPause.css({
                            //'left' : controls_left_pos
                        })

                        controls_left_pos += _conPlayPause.outerWidth() + 10;




                    }


                    if (o.skinwave_mode == 'small' && is_flashplayer && o.settings_backup_type == 'full') {
                        controls_left_pos = 140;
                        cthis.find('.meta-artist-con').hide();
                    }


                    //== adding the prev and next buttons
                    if (o.parentgallery && typeof(o.parentgallery) != 'undefined' && o.disable_player_navigation != 'on') {



                        //if(o.skinwave_mode!='small') {
                        //
                        //    if (cthis.find('.prev-btn').eq(0).css('display') != 'none') {
                        //
                        //        cthis.find('.prev-btn').eq(0).css({
                        //            'top': sh + 19
                        //            , 'left': controls_left_pos
                        //        })
                        //        controls_left_pos += 30;
                        //        cthis.find('.next-btn').eq(0).css({
                        //            'top': sh + 19
                        //            , 'left': controls_left_pos
                        //        })
                        //        controls_left_pos += 42;
                        //    }
                        //
                        //}
                    }


                    if (_metaArtistCon && _metaArtistCon.css('display') != 'none') {
                        if (o.skinwave_mode == 'small') {
                            controls_left_pos += 10;
                        }

                        if (!(o.design_skin == 'skin-wave' && o.skinwave_mode == 'small')) {
                            _metaArtistCon.css({
                                //'left': controls_left_pos
                            });

                            if (o.design_skin == 'skin-wave' && o.skinwave_mode != 'small') {
                                _metaArtistCon.css({
                                    //'width': tw - controls_left_pos - _apControlsRight.outerWidth()
                                });
                            }
                        }

                        controls_left_pos += _metaArtistCon.outerWidth();

                        //console.info(_metaArtistCon, _metaArtistCon.outerWidth());
                    }





                    controls_right_pos = 0;

                    if (_controlsVolume && _controlsVolume.css('display') != 'none') {
                        controls_right_pos += 55;
                    }

                    //cthis.find('.btn-menu-state').eq(0).css({
                    //    'right': controls_right_pos
                    //})
                    //
                    //if(cthis.find('.btn-menu-state').eq(0).css('display')!='none'){
                    //    controls_right_pos += cthis.find('.btn-menu-state').eq(0).outerWidth();
                    //}

                    //if(o.skinwave_mode!='small') {
                    //    //console.info('ceva',controls_right_pos,cthis.find('.btn-embed-code-con'),o.skinwave_mode);
                    //    cthis.find('.btn-embed-code-con').eq(0).css({
                    //        'right': controls_right_pos+'px'
                    //    })
                    //}
                    //
                    //if(cthis.find('.btn-menu-state').eq(0).css('display')!='none'){
                    //    controls_right_pos += cthis.find('.btn-menu-state').eq(0).outerWidth();
                    //}



                    // ---------- calculate dims small
                    if (o.skinwave_mode == 'small') {
                        controls_left_pos += 10;
                        _scrubbar.css({
                            //'left' : controls_left_pos
                        })


                        //sw =  ( tw - controls_left_pos - controls_right_pos );




                        if (o.scrubbar_tweak_overflow_hidden == 'on') {
                            _scrubbar.css({
                                'left': _apControlsLeft.width(),
                                'width': tw - _apControlsLeft.width() - _apControlsRight.outerWidth()
                            })


                        }

                        sw = _scrubbar.width();

                        if (o.scrubbar_tweak_overflow_hidden == 'on') {
                            //sw = parseInt(_scrubbar.css('width'),10);
                            sw = tw - _apControlsLeft.width() - _apControlsRight.outerWidth();
                        }
                        //console.info(sw,controls_left_pos,controls_right_pos);


                        controls_right_pos += 10;



                        _scrubbar.find('.scrub-bg-img').eq(0).css({
                            'width': sw
                        })
                        _scrubbar.find('.scrub-prog-img').eq(0).css({
                            'width': sw
                        })
                        //cthis.find('.comments-holder').eq(0).css({
                        //    'width' :  _scrubbar.width()
                        //    ,'left' : controls_left_pos
                        //});



                    }



                    if (o.skinwave_wave_mode == 'canvas') {

                        if (cthis.attr('data-pcm')) {


                            if (_scrubbarbg_canvas.width() == 100) {
                                _scrubbarbg_canvas.width(_scrubbar.width());
                            }

                            if(_scrubbarbg_canvas && _theThumbCon && _apControls.parent(), _scrubbar){

                                // console.info("HMM PCM DRAW", _scrubbarbg_canvas, _scrubbarbg_canvas.width(), _scrubbar.width(), _apControls.width(), _apControls.parent().width(), tw, _theThumbCon.width());
                            }
                            draw_canvas(_scrubbarbg_canvas.get(0), cthis.attr('data-pcm'), "#" + o.design_wave_color_bg,{call_from: 'canvas_normal_pcm_bg'});
                            draw_canvas(_scrubbarprog_canvas.get(0), cthis.attr('data-pcm'), "#" + o.design_wave_color_progress);
                        }
                    }
                }

                if (o.design_skin == 'skin-pro') {

                    controls_right_pos = 10;

                    if (_controlsVolume && _controlsVolume.css('display') != 'none') {
                        controls_right_pos += 60;
                    }

                    _totalTime.css({
                        'left': 'auto',
                        'right': controls_right_pos
                    })
                    controls_right_pos += 50;


                    _currTime.css({
                        'left': 'auto',
                        'right': controls_right_pos
                    })
                }

                if (o.design_skin == 'skin-minimal') {

                    // console.warn(skin_minimal_button_size);
                    if(skin_minimal_canvasplay){
                        skin_minimal_canvasplay.style.width = skin_minimal_button_size;
                        skin_minimal_canvasplay.width = skin_minimal_button_size;
                        skin_minimal_canvasplay.style.height = skin_minimal_button_size;
                        skin_minimal_canvasplay.height = skin_minimal_button_size;
                        skin_minimal_canvaspause.style.width = skin_minimal_button_size;
                        skin_minimal_canvaspause .width = skin_minimal_button_size;
                        skin_minimal_canvaspause.style.height = skin_minimal_button_size;
                        skin_minimal_canvaspause .height = skin_minimal_button_size;

                        _conPlayPause.css({
                            'width' : skin_minimal_button_size
                            ,'height' : skin_minimal_button_size
                        })
                    }
                }


                if (o.design_skin == 'skin-default') {
                    if (_currTime) {
                        //console.info(o.design_skin, parseInt(_metaArtistCon.css('left'),10) + _metaArtistCon.outerWidth() + 10);
                        var _metaArtistCon_l = parseInt(_metaArtistCon.css('left'), 10);
                        var _metaArtistCon_w = _metaArtistCon.outerWidth();

                        if (_metaArtistCon.css('display') == 'none') {
                            _metaArtistCon_w = 0;
                        }
                        if (isNaN(_metaArtistCon_l)) {
                            _metaArtistCon_l = 20;
                        }
                        //                        console.info(o.design_skin, _currTime,  _metaArtistCon, _metaArtistCon.css('left'), parseInt(_metaArtistCon.css('left'),10), parseInt(_metaArtistCon.css('left'),10) + _metaArtistCon_w + 10);

                        _currTime.css({
                            //'left': _metaArtistCon_l + _metaArtistCon_w + 10
                        })
                        _totalTime.css({
                            //'left': _metaArtistCon_l + _metaArtistCon_w + 55
                        })
                        /*
                         */
                    }

                }

                if (o.design_skin == 'skin-minion') {
                    //console.info();
                    aux2 = parseInt(_conControls.find('.con-playpause').eq(0).offset().left, 10) - parseInt(_conControls.eq(0).offset().left, 10) - 18;
                    _conControls.find('.prev-btn').eq(0).css({
                        'top': 0,
                        'left': aux2
                    })
                    aux2 += 36;
                    _conControls.find('.next-btn').eq(0).css({
                        'top': 0,
                        'left': aux2
                    })
                }


                if (o.embedded == 'on') {
                    //console.info(window.frameElement)
                    if (window.frameElement) {
                        //window.frameElement.height = cthis.height();
                        //console.info(window.frameElement.height, cthis.outerHeight())


                        var args = {
                            height: cthis.outerHeight()
                        };


                        if (o.embedded_iframe_id) {

                            args.embedded_iframe_id = o.embedded_iframe_id;
                        }


                        var message = {
                            name: "resizeIframe",
                            params: args
                        };
                        window.parent.postMessage(message, '*');
                    }

                }





            }

            function mouse_volumebar(e) {
                var _t = $(this);

                //var mx = e.clientX - _controlsVolume.offset().left;
                if (e.type == 'mousemove') {

                    //console.info(volume_dragging, mx);


                    if (volume_dragging) {
                        aux = (e.pageX - (_controlsVolume.find('.volume_static').eq(0).offset().left)) / (_controlsVolume.find('.volume_static').eq(0).width());

                        if (_t.parent().hasClass('volume-holder') || _t.hasClass('volume-holder')) {



                        }

                        if (o.design_skin == 'skin-redlights') {
                            aux *= 10;

                            aux = Math.round(aux);

                            //console.info(aux);
                            aux /= 10;
                        }


                        set_volume(aux, {
                            call_from: "set_by_mousemove"
                        });
                        muted = false;
                    }

                }
                if (e.type == 'mouseleave') {

                }
                if (e.type == 'click') {

                    //console.log(_t, _t.offset().left)

                    aux = (e.pageX - (_controlsVolume.find('.volume_static').eq(0).offset().left)) / (_controlsVolume.find('.volume_static').eq(0).width());

                    if (_t.parent().hasClass('volume-holder')) {


                        aux = 1 - ((e.pageY - (_controlsVolume.find('.volume_static').eq(0).offset().top)) / (_controlsVolume.find('.volume_static').eq(0).height()));

                    }
                    if(_t.hasClass('volume-holder')) {
                        aux = 1 - ((e.pageY - (_controlsVolume.find('.volume_static').eq(0).offset().top)) / (_controlsVolume.find('.volume_static').eq(0).height()));

                        console.info(aux);

                    }

                    //console.info(aux);

                    set_volume(aux, {
                        call_from: "set_by_mouseclick"
                    });
                    muted = false;
                }

                if (e.type == 'mousedown') {

                    volume_dragging = true;
                    cthis.addClass('volume-dragging');




                    aux = (e.pageX - (_controlsVolume.find('.volume_static').eq(0).offset().left)) / (_controlsVolume.find('.volume_static').eq(0).width());

                    if (_t.parent().hasClass('volume-holder')) {


                        aux = 1 - ((e.pageY - (_controlsVolume.find('.volume_static').eq(0).offset().top)) / (_controlsVolume.find('.volume_static').eq(0).height()));

                    }

                    //console.info(aux);

                    set_volume(aux, {
                        call_from: "set_by_mousedown"
                    });
                    muted = false;
                }
                if (e.type == 'mouseup') {

                    volume_dragging = false;
                    cthis.removeClass('volume-dragging');

                }

            }

            function mouse_scrubbar(e) {
                var mousex = e.pageX;


                if ($(e.target).hasClass('sample-block-start') || $(e.target).hasClass('sample-block-end')) {
                    return false;
                }

                if (e.type == 'mousemove') {
                    _scrubbar.children('.scrubBox-hover').css({
                        'left': (mousex - _scrubbar.offset().left)
                    })
                }
                if (e.type == 'mouseleave') {

                }
                if (e.type == 'click') {


                    if (audioBuffer) {
                        time_total = audioBuffer.duration;
                    }


                    var aux = ((e.pageX - (_scrubbar.offset().left)) / (sw) * time_total);
                    if (is_flashplayer == true) {
                        aux = (e.pageX - (_scrubbar.offset().left)) / (sw);
                    }
                    //console.info(e.target,e.pageX, (_scrubbar.offset().left), (sw), time_total, aux);

                    if (sample_time_start > 0) {
                        aux -= sample_time_start;
                    }

                    if (o.fakeplayer) {


                        var args = {
                            type: type_for_fake_feed,
                            fakeplayer_is_feeder: 'on'
                        }

                        //o.fakeplayer.get(0).api_change_media(cthis, args);
                        setTimeout(function() {

                            if (o.fakeplayer.get(0) && o.fakeplayer.get(0).api_pause_media) {

                                o.fakeplayer.get(0).api_seek_to_perc(aux / time_total,{
                                    'call_from': 'from_feeder_to_feed'
                                });
                            }
                        }, 50);
                    }

                    seek_to(aux, {
                        'call_from': 'mouse_scrubbar'
                    });

                    // return false;

                    if (playing == false) {
                        play_media({
                            'call_from':'mouse_scrubbar'
                        });
                    }
                }

            }

            function seek_to_perc(argperc, pargs) {
                seek_to((argperc * time_total),pargs);
            }

            function seek_to(arg, pargs) {
                //arg = nr seconds


                //console.info(_feed_fakePlayer);

                var margs = {
                    'call_from': 'default'
                    ,'call_from_type': 'default' // -- default or "auto" or "user action"
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                if(margs.call_from=='from_feeder_to_feed'){

                }


                // console.info('seek_to', arg, margs);


                if (o.fakeplayer) {


                    var args = {
                        type: type_for_fake_feed,
                        fakeplayer_is_feeder: 'on'
                    }

                    //o.fakeplayer.get(0).api_change_media(cthis, args);
                    setTimeout(function() {

                        if (o.fakeplayer.get(0) && o.fakeplayer.get(0).api_pause_media) {

                            if(margs.call_from!='from_playfrom'){
                                o.fakeplayer.get(0).api_seek_to(arg,{
                                    'call_from': 'from_feeder_to_feed'
                                });
                            }

                        }
                    }, 50);

                    return false;
                }


                if (type == 'youtube') {
                    _cmedia.seekTo(arg);
                }

                if (type == 'audio') {
                    if (audioBuffer && audioBuffer != 'waiting') {

                        //console.info('arg - ',arg);
                        lasttime_inseconds = arg;
                        audioCtx.currentTime = lasttime_inseconds;

                        if (inter_audiobuffer_workaround_id != 0) {
                            time_curr = arg;
                        }

                        pause_media({
                            'audioapi_setlasttime': false
                        });
                        play_media({
                            'call_from':'audio_buffer'
                        });
                    } else {
                        if (is_flashplayer == true) {

                            if (o.settings_backup_type == 'light') {
                                if (str_ie8 == '') {
                                    eval("_cmedia.fn_seek_to" + cthisId + "(" + arg + ")");
                                }
                            }
                            play_media();
                        } else {

                            if (o.design_skin == 'skin-pro') {
                                // var aux = parseInt(Math.easeOutQuad_rev(arg/totalDuration, 0, sw,1), 10);
                                //
                                // console.info(arg/totalDuration, aux/sw, arg, aux, sw)
                            }

                            if(_cmedia && _cmedia.currentTime){

                                _cmedia.currentTime = arg;
                            }

                            // console.warn('hmm');
                            return false;
                        }
                    }

                }


            }

            function seek_to_onlyvisual(argperc) {

                //if(debug_var){
                //    console.info('seek_to_onlyvisual()',argperc,cthis);
                //    debug_var = false;
                //}

                //console.info(time_total);
                if (time_total == 0) {


                    if (_cmedia && _cmedia.duration) {
                        time_total = _cmedia.duration;
                    }
                    //
                    //if(debug_var){
                    //    //console.info('seek_to_onlyvisual()', o.type, argperc,time_curr, time_total,_cmedia, _cmedia.duration);
                    //    debug_var = false;
                    //}
                }

                time_curr = argperc * time_total;



                //console.info(time_curr,argperc,time_total);
                //check_time();
            }

            function set_playback_speed(arg) {
                //=== outputs a playback speed from 0.1 to 10

                if (type == 'youtube') {
                    _cmedia.setPlaybackRate(arg);
                }
                if (type == 'audio') {
                    if (is_flashplayer == false) {
                        _cmedia.playbackRate = arg;
                    }
                }

            }

            function set_volume(arg, pargs) {
                // -- outputs a volume from 0 to 1

                var margs = {

                    'call_from': 'default'
                };

                if (pargs) {
                    margs = $.extend(margs, pargs);
                }

                if(arg>1){
                    arg = 1;
                }
                if(arg<0){
                    arg = 0;
                }


                if(margs.call_from=='from_fake_player_feeder_from_init_loaded'){
                    // -- lets prevent call from the init_loaded set_volume if the volume has been changed
                    if(_feed_fakePlayer){


                        // console.info('volume_set_by_user - ',volume_set_by_user);

                        if(o.default_volume!='default'){
                            volume_set_by_user = true;
                        }


                        if(volume_set_by_user){
                            return false;
                        }else{

                            volume_set_by_user= true;

                            console.info("SET VOLUME BY USER", cthis);
                        }
                    }
                }

                if(margs.call_from=='set_by_mouseclick' || margs.call_from=='set_by_mousemove'){
                    volume_set_by_user = true;
                }

                // console.log("set_volume()",arg, cthis, margs);

                if (type == 'youtube') {
                    _cmedia.setVolume(arg * 100);
                }
                if (type == 'audio') {
                    if (is_flashplayer == true) {


                        if (o.settings_backup_type == 'light') {
                            if (str_ie8 == '') {
                                eval("_cmedia.fn_volumeset" + cthisId + "(arg)");
                            }
                        }
                        //play_cmedia();
                    } else {
                        if(_cmedia){

                            console.info('volume - ',arg, arg* o.watermark_volume);
                            _cmedia.volume = arg;

                            if(_cwatermark){
                                _cwatermark.volume = arg* o.watermark_volume;
                            }
                        }else{


                            if(_cmedia){

                                $(_cmedia).attr('preload', 'metadata');
                            }

                        }
                    }
                }

                //console.log(_controlsVolume.children('.volume_active'));


                visual_set_volume(arg,margs);

                if(_feed_fakePlayer){
                    margs.call_from = ('from_fake_player')

                    if(_feed_fakePlayer.get(0) && _feed_fakePlayer.get(0).api_visual_set_volume(arg,margs)){

                        _feed_fakePlayer.get(0).api_visual_set_volume(arg,margs);
                    }
                }

                if(o.fakeplayer){

                    // console.info(margs);
                    if(margs.call_from != ('from_fake_player')){

                        // margs.call_from = ('from_fake_player_feeder')
                        if(margs.call_from=='from_init_loaded'){

                            margs.call_from = ('from_fake_player_feeder_from_init_loaded')
                        }else{

                            margs.call_from = ('from_fake_player_feeder')
                        }
                        if(_feed_fakePlayer.get(0) && _feed_fakePlayer.get(0).api_set_volume(arg,margs)) {
                            o.fakeplayer.get(0).api_set_volume(arg, margs);
                        }
                    }
                }

                // console.info(o.fakeplayer);
            }


            function visual_set_volume(arg,margs){



                if (_controlsVolume.hasClass('controls-volume-vertical')) {

                    //console.info('ceva');
                    _controlsVolume.find('.volume_active').eq(0).css({
                        'height': (_controlsVolume.find('.volume_static').eq(0).height() * arg)
                    });
                } else {

                    _controlsVolume.find('.volume_active').eq(0).css({
                        'width': (_controlsVolume.find('.volume_static').eq(0).width() * arg)
                    });
                }


                if (o.design_skin == 'skin-wave' && o.skinwave_dynamicwaves == 'on') {
                    //console.log(arg);
                    _scrubbar.find('.scrub-bg-img').eq(0).css({
                        'transform': 'scaleY(' + arg + ')'
                    })
                    _scrubbar.find('.scrub-prog-img').eq(0).css({
                        'transform': 'scaleY(' + arg + ')'
                    })

                    if (o.skinwave_enableReflect == 'on') {

                        if (arg == 0) {
                            cthis.find('.scrub-bg-img-reflect').fadeOut('slow');
                        } else {
                            cthis.find('.scrub-bg-img-reflect').fadeIn('slow');
                        }
                    }
                }


                if (localStorage != null && the_player_id) {

                    //console.info(the_player_id);

                    localStorage.setItem('dzsap_last_volume_' + the_player_id, arg);

                }

                last_vol = arg;
            }


            function click_mute() {
                if (muted == false) {
                    last_vol_before_mute = last_vol;
                    set_volume(0, {
                        call_from: "from_mute"
                    });
                    muted = true;
                } else {
                    set_volume(last_vol_before_mute, {
                        call_from: "from_unmute"
                    });
                    muted = false;
                }
            }

            function pause_media_visual() {


                if (o.design_animateplaypause != 'on') {
                    _conPlayPause.children('.playbtn').css({
                        'display': 'block'
                    });
                    _conPlayPause.children('.pausebtn').css({
                        'display': 'none'
                    });
                } else {

                    if (cthis.hasClass('is-playing') == false) {
                        return false;
                    }


                    if (o.design_skin == 'skin-redlights' || o.design_skin == 'skin-steel') {

                        _conPlayPause.children('.pausebtn').css('opacity', 1);
                        _conPlayPause.children('.pausebtn').animate({
                            'opacity': '0'
                        }, {
                            queue: false,
                            duration: 300
                        });


                        //console.info(_conPlayPause);


                        _conPlayPause.children('.playbtn').css({
                            'opacity': 0,
                            'visibility': 'visible',
                            'display': 'block'
                        });
                        _conPlayPause.children('.playbtn').animate({
                            'opacity': '1'
                        }, {
                            queue: false,
                            duration: 300
                        });





                    } else {

                        _conPlayPause.children('.playbtn').stop().fadeIn('fast');
                        _conPlayPause.children('.pausebtn').stop().fadeOut('fast');
                    }
                }


                _conPlayPause.removeClass('playing');
                cthis.removeClass('is-playing');
                playing = false;


                if(cthis.parent().hasClass('zoomsounds-wrapper-bg-center')){
                    cthis.parent().removeClass('is-playing');
                }


            }

            function pause_media(pargs) {
                //console.log('pause_media()', cthis);

                if (_feed_fakePlayer) {
                    //console.warn('has _feed_fakePlayer and will pause that too - ',_feed_fakePlayer);
                }

                var margs = {
                    'audioapi_setlasttime': true,
                    'donot_change_media': false
                };

                if (destroyed) {
                    return false;
                }

                if (pargs) {
                    margs = $.extend(margs, pargs);
                }



                pause_media_visual();


                //console.info(margs.donot_change_media);
                if (margs.donot_change_media != true) {


                    //console.info(o.fakeplayer);
                    if (o.fakeplayer != null) {

                        var args = {
                            type: type_for_fake_feed,
                            fakeplayer_is_feeder: 'on'
                        }
                        //console.info(playing, args, o.fakeplayer);
                        // o.fakeplayer.get(0).api_change_media(cthis, args);
                        setTimeout(function() {

                            if (o.fakeplayer.get(0) && o.fakeplayer.get(0).api_pause_media) {

                                o.fakeplayer.get(0).api_pause_media();
                            }
                        }, 100);

                        playing = false;
                        cthis.removeClass('is-playing');


                        if(cthis.parent().hasClass('zoomsounds-wrapper-bg-center')){
                            cthis.parent().removeClass('is-playing');
                        }

                        return;
                    }



                }


                if (type == 'youtube') {
                    _cmedia.pauseVideo();
                }
                if (type == 'audio') {

                    if (audioBuffer != null) {
                        //console.log(audioCtx.currentTime, audioBuffer.duration);
                        //console.log(lasttime_inseconds);
                        ///==== on safari we need to wait a little for the sound to load
                        if (audioBuffer != 'placeholder' && audioBuffer != 'waiting') {
                            if (margs.audioapi_setlasttime == true) {
                                lasttime_inseconds = audioCtx.currentTime;
                            }
                            //console.log('trebuie doar la pauza', lasttime_inseconds);

                            if(webaudiosource && webaudiosource.stop){

                                webaudiosource.stop(0);
                            }
                        }
                    } else {
                        if (is_flashplayer == true && o.settings_backup_type == 'light' && cthis.css('display') != 'none') {
                            if (o.settings_backup_type == 'light') {
                                eval("_cmedia.fn_pausemedia" + cthisId + "()");
                            }
                        } else {
                            if (_cmedia) {
                                if (_cmedia.pause) {
                                    _cmedia.pause();
                                }
                            }
                            if (_cwatermark && _cwatermark.pause) {

                                _cwatermark.pause();

                            }
                        }
                    }


                }

                if (_feed_fakePlayer) {

                    _feed_fakePlayer.get(0).api_pause_media_visual();
                }


                playing = false;
                cthis.removeClass('is-playing');


                if(cthis.parent().hasClass('zoomsounds-wrapper-bg-center')){
                    cthis.parent().removeClass('is-playing');
                }

            }

            function play_media_visual(margs) {



                if (o.design_animateplaypause != 'on') {

                    _conPlayPause.children('.playbtn').css({
                        'display': 'none'
                    });
                    _conPlayPause.children('.pausebtn').css({
                        'display': 'block'
                    });
                } else {


                    if (o.design_skin == 'skin-redlights' || o.design_skin == 'skin-steel') {

                        _conPlayPause.children('.playbtn').css('opacity', 1);
                        _conPlayPause.children('.playbtn').animate({
                            'opacity': '0'
                        }, {
                            queue: false,
                            duration: 600
                        });


                        _conPlayPause.children('.pausebtn').css({
                            'opacity': 0,
                            'visibility': 'visible',
                            'display': 'block'
                        });
                        _conPlayPause.children('.pausebtn').animate({
                            'opacity': '1'
                        }, {
                            queue: false,
                            duration: 600
                        });


                    } else {

                        _conPlayPause.children('.playbtn').stop().fadeOut('fast');
                        _conPlayPause.children('.pausebtn').stop().fadeIn('fast');
                    }
                }





                playing = true;
                cthis.addClass('is-playing');
                cthis.addClass('first-played');

                _conPlayPause.addClass('playing');

                if(cthis.parent().hasClass('zoomsounds-wrapper-bg-center')){
                    cthis.parent().addClass('is-playing');
                }

                //console.info(cthis, margs);

                if (action_audio_play) {
                    action_audio_play(cthis);
                }
                if (action_audio_play2) {
                    action_audio_play2(cthis);
                }


            }

            function play_media(pargs) {
                // console.log('play_media()',pargs,cthis, 'type - ',type);

                //                console.log(dzsap_list);


                if(is_ios() && audioBuffer == 'waiting'){
                    setTimeout(function(){
                        play_media(pargs);
                    },500);
                    return false;
                }



                var margs = {
                    'api_report_play_media': true
                    ,'call_from': 'default'
                }

                if (pargs) {
                    margs = $.extend(margs, pargs)
                }

                if (cthis.hasClass('media-setuped') == false) {
                    console.info('warning: media not setuped, there might be issues', cthis.attr('id'));
                }



                if(margs.call_from=='feed_to_feeder'){

                    if (cthis.hasClass('dzsap-loaded')==false) {

                        init_loaded();

                        var delay = 300;

                        if(is_ios()){

                        }
                        if(is_android_good()){
                            delay = 0;
                        }

                        if(delay){

                            setTimeout(function () {

                                play_media(margs);
                            },delay);
                        }else{

                            play_media(margs);
                        }

                        return false;
                    }
                }


                //console.info(o.type);
                if (type != 'fake') {

                    //return false;
                }
                for (i = 0; i < dzsap_list.length; i++) {

                    //                    console.info(!is_ie8(), dzsap_list[i].get(0), typeof dzsap_list[i].get(0)!="undefined", typeof dzsap_list[i].get(0).api_pause_media!="undefined")
                    if (!is_ie8() && typeof dzsap_list[i].get(0) != "undefined" && typeof dzsap_list[i].get(0).api_pause_media != "undefined" && dzsap_list[i].get(0) != cthis.get(0)) {

                        //console.info('try to pause', dzsap_list[i].get(0),dzsap_list[i].data('type_audio_stop_buffer_on_unfocus'))
                        if (dzsap_list[i].data('type_audio_stop_buffer_on_unfocus') && dzsap_list[i].data('type_audio_stop_buffer_on_unfocus') == 'on') {
                            dzsap_list[i].get(0).api_destroy_for_rebuffer();
                        } else {

                            dzsap_list[i].get(0).api_pause_media({
                                'audioapi_setlasttime': false
                            });
                        }
                    }
                }

                if (destroyed_for_rebuffer) {

                    setup_media();


                    if (isInt(playfrom)) {
                        seek_to(playfrom,{
                            'call_from':'destroyed_for_rebuffer_playfrom'
                        });
                    }

                    destroyed_for_rebuffer = false;
                }

                // console.info(o.google_analytics_send_play_event, window._gaq, google_analytics_sent_play_event);
                if (o.google_analytics_send_play_event == 'on' && window._gaq && google_analytics_sent_play_event == false) {
                    //if(window.console){ console.info( 'sent event'); }
                    window._gaq.push(['_trackEvent', 'ZoomSounds Play', 'Play', 'zoomsounds play - ' + cthis.attr('data-source')]);
                    google_analytics_sent_play_event = true;
                }
                // console.info(o.google_analytics_send_play_event, window.ga, google_analytics_sent_play_event);

                if (!window.ga) {
                    if (window.__gaTracker) {
                        window.ga = window.__gaTracker;
                    }
                }
                if (o.google_analytics_send_play_event == 'on' && window.ga && google_analytics_sent_play_event == false) {
                    if (window.console) {
                        console.info('sent event');
                    }
                    google_analytics_sent_play_event = true;
                    window.ga('send', {
                        hitType: 'event',
                        eventCategory: 'zoomsounds play - ' + cthis.attr('data-source'),
                        eventAction: 'play',
                        eventLabel: 'zoomsounds play - ' + cthis.attr('data-source')
                    });
                }

                //===media functions

                if (_feed_fakePlayer) {

                    //console.info(cthis, _feed_fakePlayer);
                    _feed_fakePlayer.get(0).api_play_media_visual({
                        'api_report_play_media': false
                    });
                }

                // console.info("TYPE IS ",type, o.fakeplayer);

                if (o.fakeplayer) {

                    //console.info(o.fakeplayer);
                    var args = {
                        type: type_for_fake_feed,
                        fakeplayer_is_feeder: 'on',
                        call_from: 'play_media_audioplayer'
                    }
                    //console.info(playing, args, o.fakeplayer);
                    o.fakeplayer.get(0).api_change_media(cthis, args);
                    setTimeout(function() {

                        if (o.fakeplayer.get(0) && o.fakeplayer.get(0).api_play_media) {

                            o.fakeplayer.get(0).api_play_media({
                                'call_from':'feed_to_feeder'
                            });
                        }
                    }, 100);



                    // console.info('ajax view submitted', cthis, ajax_view_submitted);
                    if (ajax_view_submitted == 'off') {
                        ajax_submit_views();
                    }

                    return;
                }



                if (type == 'youtube') {
                    //console.info(_cmedia);
                    if (_cmedia && _cmedia.playVideo) {

                        _cmedia.playVideo();
                    }
                }
                if (type == 'normal') {
                    type = 'audio';
                }
                if (type == 'audio') {


                    console.log('getting to play', type, audioBuffer, is_flashplayer, pargs);
                    if (audioBuffer ) {
                        //console.log(audioBuffer);
                        ///==== on safari we need to wait a little for the sound to load
                        if (audioBuffer != 'placeholder'  && audioBuffer != 'waiting') {
                            webaudiosource = audioCtx.createBufferSource();
                            webaudiosource.buffer = audioBuffer;
                            //javascriptNode.connect(audioCtx.destination);
                            webaudiosource.connect(audioCtx.destination);

                            webaudiosource.connect(analyser)
                            //analyser.connect(audioCtx.destination);
                            //console.log("play ctx", lasttime_inseconds);
                            webaudiosource.start(0, lasttime_inseconds);
                        } else {
                            return;
                        }

                    } else {
                        // -- no audiobuffer
                        if (is_flashplayer == true && cthis.css('display') != 'none') {
                            //alert("_cmedia.fn_playMedia"+cthisId+"()");
                            //console.log(cthis);
                            if (o.settings_backup_type == 'light') {
                                eval("_cmedia.fn_playmedia" + cthisId + "()");
                            }

                        } else {
                            if (_cmedia) {

                                // console.info('actually playing _cmedia.play', _cmedia, cthis)
                                if (_cmedia.play ) {
                                    setTimeout(function() {
                                        _cmedia.play();
                                    }, 1000)
                                }
                            }
                            //console.info('watermark - .play', _cwatermark)
                            if (_cwatermark) {

                                //console.info('watermark - .play', _cwatermark, _cwatermark.play)
                                if (_cwatermark.play ) {
                                    _cwatermark.play();
                                }
                            }
                        }
                    }

                }



                play_media_visual(margs);




                //console.info(ajax_view_submitted);



                if(_feed_fakePlayer){
                    _feed_fakePlayer.get(0).api_try_to_submit_view();
                }else{

                    try_to_submit_view();
                }
            }

            function try_to_submit_view(){
                // console.info('try_to_submit_view', cthis, ajax_view_submitted);
                if (ajax_view_submitted == 'auto') {
                    ajax_view_submitted = 'off';
                }
                if (ajax_view_submitted == 'off') {
                    ajax_submit_views();
                }
            }



            function formatTime(arg) {
                //formats the time
                var s = Math.round(arg);
                var m = 0;
                if (s > 0) {
                    while (s > 59 && s < 3000000 && isFinite(s)) {
                        m++;
                        s -= 60;
                    }
                    return String((m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s);
                } else {
                    return "00:00";
                }
            }
            return this;
        })
    }

    window.dzsap_init = function(selector, settings) {

        console.log(selector);
        if (typeof(settings) != "undefined" && typeof(settings.init_each) != "undefined" && settings.init_each == true) {
            var element_count = 0;
            for (var e in settings) {
                element_count++;
            }
            if (element_count == 1) {
                settings = undefined;
            }

            $(selector).each(function() {
                var _t = $(this);
                _t.audioplayer(settings)
            });
        } else {
            $(selector).audioplayer(settings);
        }

    };








    //////=======
    // -- the nav
    /////========

    $.fn.zoomsounds_nav = function(o) {
        var defaults = {


        }


        if (typeof o == 'undefined') {
            if (typeof $(this).attr('data-options') != 'undefined' && $(this).attr('data-options') != '') {
                var aux = $(this).attr('data-options');
                aux = 'var aux_opts = ' + aux;
                eval(aux);
                o = aux_opts;
            }
        }


        o = $.extend(defaults, o);
        this.each(function () {

            //console.info("INITED");
            var cgallery = $(this);
            var cchildren = cgallery.children(),
                cgalleryId = 'ag1';
            var currNr = -1 // -- the current player that is playing

                , currNr_2 = -1
                , lastCurrNr = 0
                , nrChildren = 0

        });




    };




















    //////=======
    // AUDIO GALLERY
    /////========

    $.fn.audiogallery = function(o) {
        var defaults = {
            design_skin: 'skin-default',
            cueFirstMedia: 'on',
            autoplay: 'off'
            ,settings_enable_linking: 'off' // -- use html5 history to remember last position in the gallery
            ,autoplayNext: 'on',
            design_menu_position: 'bottom',
            design_menu_state: 'open' // -- options are "open" or "closed", this sets the initial state of the menu, even if the initial state is "closed", it can still be opened by a button if you set design_menu_show_player_state_button to "on"
            ,design_menu_show_player_state_button: 'off' // -- show a button that allows to hide or show the menu
            ,design_menu_width: 'default'
            ,design_menu_height: '200'
            ,design_menu_space: 'default'
            ,settings_php_handler: '',
            design_menuitem_width: 'default',
            design_menuitem_height: 'default',
            design_menuitem_space: 'default',
            disable_menu_navigation: 'off'
            ,menu_nav_type: 'mousemove' // -- mousemove or scroller or all
            ,menu_facebook_share: 'auto' // -- mousemove or scroller or all
            ,enable_easing: 'off' // -- enable easing for menu animation
            ,settings_ap: {}
            ,transition: 'fade' //fade or direct
            ,embedded: 'off'
            ,settings_mode: 'mode-normal' // mode-normal or mode-showall

        }




        if (typeof o == 'undefined') {
            if (typeof $(this).attr('data-options') != 'undefined' && $(this).attr('data-options') != '') {
                var aux = $(this).attr('data-options');
                aux = 'var aux_opts = ' + aux;
                eval(aux);
                o = aux_opts;
            }
        }


        o = $.extend(defaults, o);
        this.each(function() {

            //console.info("INITED");
            var cgallery = $(this);
            var cchildren = cgallery.children(),
                cid = 'ag1';
            var currNr = -1 // -- the current player that is playing

                ,currNr_2 = -1
                ,lastCurrNr = 0
                ,nrChildren = 0
                ,tempNr = 0;
            var busy = true;
            var i = 0;
            var ww, wh, tw, th
                , nc_maindim // -- nav clip size
                , nm_maindim // -- nav main total size
                , sw = 0 // -- scrubbar width
                ,
                sh, spos = 0 // --  scrubbar prog pos
            ;
            var _sliderMain, _sliderClipper, _navMain, _navClipper, _cache;
            var busy = false,
                playing = false,
                muted = false,
                loaded = false,
                first = true,
                skin_redlight_give_controls_right_to_all_players = false // -- if the mode is mode-showall and the skin of the player is redlights, then make all players with controls right
            ;
            var time_total = 0,
                time_curr = 0;
            var last_vol = 1,
                last_vol_before_mute = 1;
            var inter_check, inter_checkReady;
            var skin_minimal_canvasplay, skin_minimal_canvaspause;
            var is_flashplayer = false;
            var data_source;

            var aux_error = 20; //==erroring for the menu scroll

            var res_thumbh = false;
            var trying_to_get_track_data = false;

            var str_ie8 = '';

            var arr_menuitems = [];
            var track_data = []; // -- the whole track data views / likes etc.

            var str_alertBeforeRate = 'You need to comment or rate before downloading.';



            var duration_viy = 20;

            var target_viy = 0;

            var begin_viy = 0;

            var finish_viy = 0;

            var change_viy = 0;


            if (window.dzsap_settings && typeof(window.dzsap_settings.str_alertBeforeRate) != 'undefined') {
                str_alertBeforeRate = window.dzsap_settings.str_alertBeforeRate;
            }

            cgallery.get(0).currNr_2 = -1; // -- we use this as backup currNR for mode-showall ( hack )

            init();

            function init() {




                if (o.design_menu_width == 'default') {
                    o.design_menu_width = '100%';
                }
                if (o.design_menu_height == 'default') {
                    o.design_menu_height = '200';
                }


                if(cgallery.hasClass('skin-wave')){
                    o.design_skin = 'skin-wave';
                }
                if(cgallery.hasClass('skin-default')){
                    o.design_skin = 'skin-default';
                }
                if(cgallery.hasClass('skin-aura')){
                    o.design_skin = 'skin-aura';
                }


                cgallery.addClass(o.settings_mode);


                cgallery.append('<div class="slider-main"><div class="slider-clipper"></div></div>');

                cgallery.addClass('menu-position-' + o.design_menu_position);

                _sliderMain = cgallery.find('.slider-main').eq(0);


                var auxlen = cgallery.find('.items').children().length;

                // --- if there is a single audio player in the gallery - theres no point of a menu
                if (auxlen == 0 || auxlen == 1) {
                    o.design_menu_position = 'none';
                    o.settings_ap.disable_player_navigation = 'on';
                }


                var aux = '<div class="nav-main zoomsounds-nav '+o.design_skin+' nav-type-'+o.menu_nav_type+'"><div class="nav-clipper"></div></div>';

                if (o.design_menu_position == 'top') {
                    _sliderMain.before(aux);
                }
                if (o.design_menu_position == 'bottom') {
                    _sliderMain.after(aux);
                }

                if(o.settings_php_handler){

                }else{
                    if(o.settings_ap.settings_php_handler){
                        o.settings_php_handler = o.settings_ap.settings_php_handler;
                    }
                }


                if(typeof cgallery.attr('id')){
                    cid = cgallery.attr('id');
                }else{

                    var ind = 0;
                    while($('ag'+ind).length==0){
                        ind++;
                    }


                    cid = 'ag'+ind;

                    cgallery.attr('id',cid);
                }



                _sliderClipper = cgallery.find('.slider-clipper').eq(0);
                _navMain = cgallery.find('.nav-main').eq(0);
                _navClipper = cgallery.find('.nav-clipper').eq(0);

                if(cgallery.children('.extra-html').length){
                    cgallery.append(cgallery.children('.extra-html'));
                }


                reinit();

                //console.info(arr_menuitems);

                if (o.disable_menu_navigation == 'on') {
                    _navMain.hide();
                }

                //                console.info(o.design_menu_height, o.design_menu_state);
                _navMain.css({
                    'height': o.design_menu_height
                })

                if (is_ios() || is_android()) {
                    _navMain.css({
                        'overflow': 'auto'
                    })
                }

                parse_track_data();

                if (o.design_menu_state == 'closed') {

                    _navMain.css({
                        'height': 0
                    })
                }else{
                    cgallery.addClass('menu-opened');
                }


                if(can_history_api()==false){
                    o.settings_enable_linking = 'off';
                }



                if (cgallery.css('opacity') == 0) {
                    cgallery.animate({
                        'opacity': 1
                    }, 1000);
                }

                $(window).bind('resize', handleResize);
                handleResize();
                setTimeout(handleResize, 1000);



                cgallery.get(0).api_skin_redlights_give_controls_right_to_all = function() {

                    // -- void f()

                    skin_redlight_give_controls_right_to_all_players = true;
                }


                if(get_query_arg(window.location.href,'audiogallery_startitem_'+cid)){
                    tempNr = Number(get_query_arg(window.location.href, 'audiogallery_startitem_'+cid));
                }


                if (o.settings_mode == 'mode-normal') {

                    goto_item(tempNr);
                } else {

                    _sliderClipper.children().each(function() {
                        var _t = $(this);

                        //console.log(_t);

                        var ind = _t.parent().children('.audioplayer,.audioplayer-tobe').index(_t);

                        if (_t.hasClass('audioplayer-tobe')) {
                            //console.info(o.settings_ap);
                            o.settings_ap.parentgallery = cgallery;
                            o.settings_ap.action_audio_play = mode_showall_listen_for_play;
                            _t.audioplayer(o.settings_ap);

                            //console.info(ind);

                            ind = String(ind + 1);

                            if (ind.length < 2) {
                                ind = '0' + ind;
                            }

                            _t.before('<div class="number-wrapper"><span class="the-number">' + ind + '</span></div>')
                            _t.after('<div class="clear for-number-wrapper"></div>')
                        }

                    })
                    //console.info('dada2', skin_redlight_give_controls_right_to_all_players);


                    if (o.settings_mode == 'mode-showall') {

                        if (skin_redlight_give_controls_right_to_all_players) {

                            _sliderClipper.children('.audioplayer').each(function() {

                                var _t = $(this);

                                //console.info(_t);

                                if (_t.find('.ap-controls-right').eq(0).prev().hasClass('controls-right') == false) {
                                    _t.find('.ap-controls-right').eq(0).before('<div class="controls-right"> </div>');
                                }
                            });
                        }
                    }
                }


                _navClipper.on('click','.menu-btn-like,.menu-facebook-share', click_menuitem);
                _navClipper.on('click','.menu-item', click_menuitem);
                cgallery.find('.download-after-rate').bind('click', click_downloadAfterRate);

                cgallery.get(0).api_goto_next = goto_next;
                cgallery.get(0).api_goto_prev = goto_prev;
                cgallery.get(0).api_goto_item = goto_item;
                cgallery.get(0).api_handle_end = handle_end;
                cgallery.get(0).api_toggle_menu_state = toggle_menu_state;
                cgallery.get(0).api_handleResize = handleResize;
                cgallery.get(0).api_player_commentSubmitted = player_commentSubmitted;
                cgallery.get(0).api_player_rateSubmitted = player_rateSubmitted;
                cgallery.get(0).api_reinit = reinit;
                cgallery.get(0).api_play_curr_media = play_curr_media;
                cgallery.get(0).api_get_nr_children = get_nr_children;


                setInterval(calculate_on_interval, 1000);



                setTimeout(init_loaded, 700);



                if (o.enable_easing == 'on') {

                    handle_frame();
                }
                //console.info(cgallery);

                cgallery.addClass('dzsag-inited');

                cgallery.addClass('transition-' + o.transition);
            }


            function init_parse_track_data(){

                if(trying_to_get_track_data){
                    return false;
                }

                trying_to_get_track_data = true;

                var data = {
                    action: 'dzsap_get_views_all',
                    postdata: '1',
                };





                if (o.settings_php_handler) {
                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            //if(typeof window.console != "undefined" ){ console.log('Ajax - get - comments - ' + response); }

                            cgallery.attr('data-track-data',response);
                            parse_track_data();

                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };
                        }
                    });
                }


            }
            function parse_track_data(){
                if(cgallery.attr('data-track-data')){
                    try{
                        track_data = JSON.parse(cgallery.attr('data-track-data'));
                    }catch (err){
                        console.info(err);
                    }
                    var foundnr = 0;

                    if(track_data && track_data.length){
                        _navMain.find('.menu-item-views').each(function(){
                            var _t2 = $(this);

                            var aux_html = _t2.html();

                            var reg_findid = /{{views_(.*?)}}/g;


                            var aux = reg_findid.exec(aux_html);

                            //console.info(aux);

                            var id ='';
                            if(aux && aux[1]) {

                                id = aux[1];

                                for(var i in track_data) {

                                    //console.info(id, track_data[i].views, aux[0])

                                    if(id==track_data[i].label || id=='ap'+track_data[i].label) {
                                        aux_html = aux_html.replace(aux[0],track_data[i].views);
                                        foundnr++;
                                        break;
                                    }
                                }


                                _t2.html(aux_html);

                            }




                        })

                        //console.warn(foundnr, track_data.length);
                        if(foundnr<track_data.length){

                            _navMain.find('.menu-item-views').each(function(){

                                var _t2 = $(this);

                                var aux_html = _t2.html();
                                var reg_findid = /{{views_(.*?)}}/g;

                                var aux = reg_findid.exec(aux_html);

                                if(aux && aux[0]) {

                                    aux_html = aux_html.replace(aux[0],0);
                                    _t2.html(aux_html);
                                }

                            })
                        }
                    }


                }

                console.info(' track_data - ' ,track_data);
            }

            function get_nr_children(){ return nrChildren; }

            function find_player_id(arg){
                if(arg.attr('data-player-id')){
                    return arg.attr('data-player-id');
                }else{
                    if(arg.attr('id')){
                        return arg.attr('id');
                    }else{
                        if(arg.attr('data-source')){
                            return clean_string(arg.attr('data-source'));
                        }
                    }
                }
            }

            function reinit() {



                //console.info('reinit()', cgallery.find('.items').eq(0).children(), cgallery.find('.items').eq(0).children().length);

                var auxlen = cgallery.find('.items').eq(0).children().length;
                arr_menuitems = [];

                var player_id = '';

                console.info('reinit - ', cgallery.find('.items').eq(0).children());

                for (i = 0; i < auxlen; i++) {
                    var _c = cgallery.find('.items').children().eq(0);

                    console.info('_c) - ',_c);


                    var auxer = {
                        'menu_description' : _c.find('.menu-description').html()
                        ,'player_id' : find_player_id(_c)
                    }

                    arr_menuitems.push(auxer)
                    //cgallery.find('.items').children().eq(0).find('.menu-description').remove();
                    _sliderClipper.append(_c);

                }







                // console.info(arr_menuitems);
                for (i = 0; i < arr_menuitems.length; i++) {
                    var extra_class = '';
                    if (arr_menuitems[i].menu_description && arr_menuitems[i].menu_description.indexOf('<div class="menu-item-thumb-con"><div class="menu-item-thumb" style="') == -1) {
                        extra_class += ' no-thumb';
                    }


                    var aux = '<div class="menu-item' + extra_class + '"  data-menu-index="'+i+'" data-gallery-id="'+cid+'" data-playerid="'+arr_menuitems[i].player_id+'">'

                    if(cgallery.hasClass('skin-aura')){
                        aux+='<div class="menu-item-number">'+(++nrChildren)+'</div>';
                    }

                    aux+=arr_menuitems[i].menu_description;


                    if(cgallery.hasClass('skin-aura') && String(arr_menuitems[i].menu_description).indexOf('menu-item-views')==1){

                        if(track_data && track_data.length>0){

                            aux+='<div class="menu-item-views"></div>';
                        }else{

                            init_parse_track_data();
                            aux+='<div class="menu-item-views">'+svg_play_icon+' '+'<span class="the-count">{{views_'+arr_menuitems[i].player_id+'}}'+'</span></div>';
                        }

                    }



                    aux+='</div>';

                    _navClipper.append(aux);



                    if(cgallery.hasClass('skin-aura')){

                        if(arr_menuitems[i].menu_description.indexOf('float-right')>-1){
                            _navClipper.children().last().addClass('has-extra-info')
                        }
                    }
                    // nrChildren++;
                }
            }

            function init_loaded() {
                // -- gallery

                cgallery.addClass('dzsag-loaded');
            }

            function click_downloadAfterRate() {
                var _t = $(this);


                if (_t.hasClass('active') == false) {
                    alert(str_alertBeforeRate)
                    return false;
                }


            }


            function play_curr_media() {

                if (typeof(_sliderClipper.children().eq(currNr).get(0)) != 'undefined') {
                    if (typeof(_sliderClipper.children().eq(currNr).get(0).api_play_media) != 'undefined') {
                        _sliderClipper.children().eq(currNr).get(0).api_play_media({
                            'call_from':'play_curr_media_gallery'
                        });
                    }

                }
            }

            function mode_showall_listen_for_play(arg) {

                //console.info('mode_showall_listen_for_play()',currNr, arg);

                if (o.settings_mode == 'mode-showall') {

                    var ind = _sliderClipper.children('.audioplayer,.audioplayer-tobe').index(arg);
                    //console.log(ind);
                    currNr = ind;
                    cgallery.get(0).currNr_2 = ind;
                    //console.info(cgallery,currNr)
                }
                //console.info('mode_showall_listen_for_play()',currNr,this, cgallery.get(0).currNr_2);
            }

            function handle_frame() {

                // -- cgallery

                if (isNaN(target_viy)) {
                    target_viy = 0;
                }

                if (duration_viy === 0) {
                    requestAnimFrame(handle_frame);
                    return false;
                }

                begin_viy = target_viy;
                change_viy = finish_viy - begin_viy;


                //console.info('handle_frame', finish_viy, duration_viy, target_viy);

                //console.log(duration_viy);
                target_viy = Number(Math.easeIn(1, begin_viy, change_viy, duration_viy).toFixed(4));;


                if (is_ios() == false && is_android() == false) {
                    _navClipper.css({
                        'transform': 'translateY(' + target_viy + 'px)'
                    });
                }


                //console.info(_blackOverlay,target_bo);;

                requestAnimFrame(handle_frame);
            }

            function ajax_submit_like(argp, playerid,pargs) {
                //only handles ajax call + result
                var mainarg = argp;
                var data = {
                    action: 'dzsap_submit_like',
                    postdata: mainarg,
                    playerid: playerid
                };

                var margs = {
                    refferer : null
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                //console.info(margs,pargs,o.settings_php_handler);



                if (o.settings_php_handler || cthis.hasClass('is-preview')) {

                    $.ajax({
                        type: "POST",
                        url: o.settings_php_handler,
                        data: data,
                        success: function(response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            if(margs.refferer){
                                margs.refferer.addClass('active');
                            }
                        },
                        error: function(arg) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + arg, arg);
                            };
                        }
                    });
                }
            }

            function toggle_menu_state() {
                if (_navMain.height() == 0) {
                    _navMain.css({
                        'height': o.design_menu_height
                    })


                    cgallery.addClass('menu-opened');
                } else {

                    _navMain.css({
                        'height': 0
                    })
                    cgallery.removeClass('menu-opened');
                }
                setTimeout(function() {
                    handleResize();
                }, 400); // -- animation delay
            }

            function handle_end() {

                if(o.autoplayNext=='on'){

                    goto_next();
                }
            }

            function player_commentSubmitted() {
                _navClipper.children('.menu-item').eq(currNr).find('.download-after-rate').addClass('active');

            }

            function player_rateSubmitted() {
                _navClipper.children('.menu-item').eq(currNr).find('.download-after-rate').addClass('active');
            }

            function calculateDims() {
                //                console.info('calculateDims');
                _sliderClipper.css('height', _sliderClipper.children().eq(currNr).height());
                //                _navMain.show();
                nc_maindim = _navMain.height();
                nm_maindim = _navClipper.outerHeight();

                //                return;
                //                console.info(nm_maindim, nc_maindim)




                // console.info('nc_maindim - ', nc_maindim);
                // console.info('nm_maindim - ', nm_maindim);
                if (nm_maindim > nc_maindim && nc_maindim > 0) {
                    _navMain.unbind('mousemove', navMain_mousemove);
                    _navMain.bind('mousemove', navMain_mousemove);
                } else {
                    _navMain.unbind('mousemove', navMain_mousemove);
                }

                if (o.embedded == 'on') {
                    //console.info(window.frameElement)
                    if (window.frameElement) {
                        window.frameElement.height = cgallery.height();
                        //console.info(window.frameElement.height, cgallery.outerHeight())
                    }
                }
            }


            function calculate_on_interval(){

                if(_navMain){

                    nm_maindim = _navClipper.outerHeight();
                }

                // console.info('nm_maindim - ' ,nc_maindim);
            }

            function navMain_mousemove(e) {
                var _t = $(this);
                var mx = e.pageX - _t.offset().left;
                var my = e.pageY - _t.offset().top;

                // console.info('navMain_mousemove', nm_maindim, nc_maindim, nm_maindim <= nc_maindim);
                if (nm_maindim <= nc_maindim) {
                    return;
                }

                nc_maindim = _navMain.outerHeight();

                //console.log(mx);

                var vix = 0;
                var viy = 0;

                viy = (my / nc_maindim) * -(nm_maindim - nc_maindim + 10 + aux_error * 2) + aux_error;
                //console.log(viy);
                if (viy > 0) {
                    viy = 0;
                }
                if (viy < -(nm_maindim - nc_maindim + 10)) {
                    viy = -(nm_maindim - nc_maindim + 10);
                }

                finish_viy = viy;

                // console.log(viy, nm_maindim, nc_maindim, (my / nc_maindim))

                if (is_ios() == false && is_android() == false) {
                    if (o.enable_easing != 'on') {
                        _navClipper.css({
                            'transform': 'translateY(' + finish_viy + 'px)'
                        });
                    }
                }


            }

            function click_menuitem(e) {
                var _t = $(this);

                if(e.type=='click'){
                    if(_t.hasClass('menu-item')){
                        var ind = _t.parent().children().index(_t);

                        goto_item(ind);
                    }
                    if(_t.hasClass('menu-btn-like')){


                        if(_t.parent().parent().attr('data-playerid')){
                            ajax_submit_like(1,_t.parent().parent().attr('data-playerid'),{
                                refferer: _t
                            });
                        }

                        //console.info(_t);
                        return false;
                    }
                    if(_t.hasClass('menu-facebook-share')){


                        if(_t.parent().parent().attr('data-playerid')){
                            ajax_submit_like(1,_t.parent().parent().attr('data-playerid'),{
                                refferer: _t
                            });
                        }

                        //console.info(_t);
                        return false;
                    }
                }

            }

            function handleResize() {

                setTimeout(function() {
                    //console.info(_sliderClipper.children().eq(currNr), _sliderClipper.children().eq(currNr).height())
                    _sliderClipper.css('height', _sliderClipper.children().eq(currNr).height());
                }, 500);

                calculateDims();

            }

            function transition_end() {

                //console.info(_sliderClipper.children().eq(lastCurrNr));

                //_sliderClipper.children().eq(lastCurrNr).hide();

                _sliderClipper.children().eq(lastCurrNr).removeClass('transitioning-out');
                _sliderClipper.children().eq(lastCurrNr).removeClass('active');

                _sliderClipper.children().eq(currNr).removeClass('transitioning-in');
                lastCurrNr = currNr;
                busy = false;
            }

            function transition_bg_end() {
                cgallery.parent().children('.the-bg').eq(0).remove();
                busy = false;
            }

            function goto_prev() {
                tempNr = currNr;
                tempNr--;
                if (tempNr < 0) {
                    tempNr = _sliderClipper.children().length - 1;
                }
                goto_item(tempNr);
            }

            function goto_next() {
                //console.info('goto_next()', currNr,cgallery.get(0).currNr_2);
                tempNr = currNr;

                if (o.settings_mode == 'mode-showall') {
                    tempNr = cgallery.get(0).currNr_2;
                }
                tempNr++;
                if (tempNr >= _sliderClipper.children().length) {
                    tempNr = 0;
                }
                goto_item(tempNr);
            }

            function goto_item(arg, pargs) {



                var margs = {

                    'ignore_arg_currNr_check' : false
                    ,'ignore_linking' : false // -- does not change the link if set to true
                    ,donotopenlink : "off"
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                //console.info('goto_item()', arg,busy);

                if (busy == true) {
                    return;
                }

                if (arg == "last") {
                    arg = _sliderClipper.children().length - 1;
                }

                //console.info('goto_item()', arg,busy, arg=="last");



                if (currNr == arg) {

                    if (_sliderClipper && _sliderClipper.children().eq(currNr).get(0) && _sliderClipper.children().eq(currNr).get(0).api_play_media) {
                        _sliderClipper.children().eq(currNr).get(0).api_play_media({
                            'call_from':'gallery'
                        });
                    }
                    return;
                }

                _cache = _sliderClipper.children('.audioplayer,.audioplayer-tobe').eq(arg);
                var currNr_last_vol = '';

                if (currNr > -1) {
                    if (typeof(_sliderClipper.children().eq(currNr).get(0)) != 'undefined') {
                        if (typeof(_sliderClipper.children().eq(currNr).get(0).api_pause_media) != 'undefined') {
                            _sliderClipper.children().eq(currNr).get(0).api_pause_media();
                        }
                        if (typeof(_sliderClipper.children().eq(currNr).get(0).api_get_last_vol) != 'undefined') {
                            currNr_last_vol = _sliderClipper.children().eq(currNr).get(0).api_get_last_vol();
                        }

                    }


                    if (o.settings_mode != 'mode-showall') {

                        //console.info(o.transition);
                        if (o.transition == 'fade') {
                            _sliderClipper.children().eq(currNr).removeClass('active');
                            _navClipper.children().eq(currNr).removeClass('active');
                            _sliderClipper.children().eq(currNr).addClass('transitioning-out');
                            _sliderClipper.children().eq(currNr).animate({

                            }, {
                                queue: false
                            });


                            setTimeout(transition_end, 300);

                            busy = true;
                        }
                        if (o.transition == 'direct') {
                            transition_end();
                        }
                    }
                }


                //============ setting settings
                if (o.settings_ap.design_skin == 'sameasgallery') {
                    o.settings_ap.design_skin = o.design_skin;
                }

                //===if this is  the first audio
                if (currNr == -1 && o.autoplay == 'on') {
                    o.settings_ap.autoplay = 'on';
                }

                //===if this is not the first audio
                if (currNr > -1 && o.autoplayNext == 'on') {
                    o.settings_ap.autoplay = 'on';
                }
                o.settings_ap.parentgallery = cgallery;

                o.settings_ap.design_menu_show_player_state_button = o.design_menu_show_player_state_button;
                o.settings_ap.cue = 'on';
                if (first == true) {
                    if (o.cueFirstMedia == 'off') {
                        o.settings_ap.cue = 'off';
                    }

                    first = false;
                }

                //============ setting settings END



                var args = $.extend(o.settings_ap);


                args.volume_from_gallery = currNr_last_vol;

                // console.info('currNr_last_vol', currNr_last_vol);

                if (_cache.hasClass('audioplayer-tobe')) {
                    _cache.audioplayer(args);
                }

                if (o.autoplayNext == 'on') {
                    if (o.settings_mode == 'mode-showall') {
                        currNr = cgallery.get(0).currNr_2;
                    }
                    if (currNr > -1 && _cache.get(0) && _cache.get(0).api_play) {
                        _cache.get(0).api_play();
                    }
                }



                if (o.settings_mode != 'mode-showall') {
                    if (o.transition == 'fade') {
                        _cache.css({})
                        _cache.animate({}, {
                            queue: false
                        })

                    }
                    if (o.transition == 'direct') {

                    }

                    _cache.addClass('transitioning-in');




                    if(_cache.attr('data-type')!='link'){
                        if(margs.ignore_linking==false && o.settings_enable_linking=='on'){
                            var stateObj = { foo: "bar" };
                            history.pushState(stateObj, null, add_query_arg(window.location.href, 'audiogallery_startitem_'+cid, (arg)));
                        }
                    }
                }

                _cache.addClass('active');
                _navClipper.children().eq(arg).addClass('active');


                if (_cache.attr("data-bgimage") != undefined && cgallery.parent().hasClass('ap-wrapper') && cgallery.parent().children('.the-bg').length > 0) {
                    cgallery.parent().children('.the-bg').eq(0).after('<div class="the-bg" style="background-image: url(' + _cache.attr("data-bgimage") + ');"></div>')
                    cgallery.parent().children('.the-bg').eq(0).css({
                        'opacity': 1
                    })


                    cgallery.parent().children('.the-bg').eq(1).css({
                        'opacity': 0
                    })
                    cgallery.parent().children('.the-bg').eq(1).animate({
                        'opacity': 1
                    }, {
                        queue: false,
                        duration: 1000,
                        complete: transition_bg_end,
                        step: function() {
                            busy = true;
                        }
                    })
                    busy = true;
                }


                //console.info('set currNr', currNr, o.settings_mode);

                if (o.settings_mode != 'mode-showall') {

                    currNr = arg;
                }



                //console.info('_sliderClipper.children().eq(currNr) - ',_sliderClipper.children().eq(currNr));
                if(_sliderClipper.children().eq(currNr).get(0) && _sliderClipper.children().eq(currNr).get(0).api_handleResize && _sliderClipper.children().eq(currNr).hasClass('media-setuped')){


                    //console.info('_sliderClipper.children().eq(currNr) - ',_sliderClipper.children().eq(currNr));
                    _sliderClipper.children().eq(currNr).get(0).api_handleResize();
                }

                calculateDims();
            }
        });
    }

    window.dzsag_init = function(selector, settings) {


        if (typeof(settings) != "undefined" && typeof(settings.init_each) != "undefined" && settings.init_each == true) {
            var element_count = 0;
            for (var e in settings) {
                element_count++;
            }
            if (element_count == 1) {
                settings = undefined;
            }

            $(selector).each(function() {
                var _t = $(this);
                _t.audiogallery(settings)
            });
        } else {
            $(selector).audiogallery(settings);
        }
    };

})(jQuery);



jQuery(document).ready(function($) {


    //console.info('song changers -> ', $('.audioplayer-song-changer'));


    $('audio.zoomsounds-from-audio').each(function() {
        var _t = $(this);
        //console.info(_t);

        _t.after('<div class="audioplayer-tobe auto-init skin-silver" data-source="' + _t.attr('src') + '"></div>');

        _t.remove();
    })

    //console.info($('.zoomvideogallery.auto-init'));
    dzsap_init('.audioplayer-tobe.auto-init', {
        init_each: true
    });
    dzsag_init('.audiogallery.auto-init', {
        init_each: true
    });


    $(document).delegate('.audioplayer-song-changer', 'click', function() {
        var _t = $(this);


        //console.info(_t);
        var _c = $(_t.attr('data-target')).eq(0);
        //console.info(_t, _t.attr('data-target'), _c, _c.get(0));



        if (_c && _c.get(0) && _c.get(0).api_change_media) {

            _c.get(0).api_change_media(_t,{
                'feeder_type':'button'
            });
        }

        return false;
    })

    $(document).delegate('.dzsap-sticktobottom .icon-hide', 'click', function() {
        var _t = $(this);

        $('.dzsap-sticktobottom .dzsap_footer').get(0).api_pause_media();

        _t.parent().parent().parent().removeClass('audioplayer-loaded');
        _t.parent().parent().parent().addClass('audioplayer-was-loaded');

        return false;
    });
    $(document).delegate('.dzsap-sticktobottom .icon-show', 'click', function() {
        var _t = $(this);


        _t.parent().parent().parent().addClass('audioplayer-loaded');
        _t.parent().parent().parent().removeClass('audioplayer-was-loaded');

        return false;
    })

    if ($('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-silver').length > 0) {
        setInterval(function() {

            //console.info($('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-silver > .audioplayer').eq(0).hasClass('dzsap-loaded'));
            if ($('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-silver  .audioplayer').eq(0).hasClass('dzsap-loaded')) {
                $('.dzsap-sticktobottom-placeholder').eq(0).addClass('active');

                if ($('.dzsap-sticktobottom').hasClass('audioplayer-was-loaded') == false) {

                    $('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-silver').addClass('audioplayer-loaded')
                }
            }
        }, 1000);
    }
    if ($('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-wave').length > 0) {
        setInterval(function() {

            // console.info($('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-wave  .audioplayer'), $('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-wave  .audioplayer').eq(0).hasClass('dzsap-loaded'));
            if ($('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-wave  .audioplayer').eq(0).hasClass('dzsap-loaded')) {
                $('.dzsap-sticktobottom-placeholder').eq(0).addClass('active');

                if ($('.dzsap-sticktobottom').hasClass('audioplayer-was-loaded') == false) {

                    $('.dzsap-sticktobottom.dzsap-sticktobottom-for-skin-wave').addClass('audioplayer-loaded')
                }
            }



        }, 1000);
    }

});



function is_mobile(){
    return is_ios() || is_android_good();
}
function is_ios() {
    // return true;
    return ((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1));
}

function is_android(){
    return is_android_good();
}

function is_android_good() {
    //return false;
    //return true;
    var ua = navigator.userAgent.toLowerCase();

    //console.info('ua - ',ua);
    return (ua.indexOf("android") > -1);
}

function is_ie() {
    if (navigator.appVersion.indexOf("MSIE") != -1) {
        return true;
    };
    return false;
};

function is_firefox() {
    if (navigator.userAgent.indexOf("Firefox") != -1) {
        return true;
    };
    return false;
};

function is_opera() {
    if (navigator.userAgent.indexOf("Opera") != -1) {
        return true;
    };
    return false;
};

function is_chrome() {
    return navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
};

function is_safari() {
    return Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
}

function version_ie() {
    return parseFloat(navigator.appVersion.split("MSIE")[1]);
};

function version_firefox() {
    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return (aversion);
    };
};

function version_opera() {
    if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return (aversion);
    };
};

function is_ie8() {
    if (is_ie() && version_ie() < 9) {
        return true;
    }
    return false;
}

function is_ie9() {
    if (is_ie() && version_ie() == 9) {
        return true;
    }
    return false;
}

function can_play_mp3() {
    var a = document.createElement('audio');
    return !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));
}

function can_canvas() {
    // check if we have canvas support
    var oCanvas = document.createElement("canvas");
    if (oCanvas.getContext("2d")) {
        return true;
    }
    return false;
}

function onYouTubeIframeAPIReady() {


    for (i = 0; i < dzsap_list.length; i++) {
        //console.log(dzsap_list[i].get(0).fn_yt_ready);
        if (dzsap_list[i].get(0) != undefined && typeof dzsap_list[i].get(0).fn_yt_ready != 'undefined') {
            dzsap_list[i].get(0).fn_yt_ready();
        }
    }
}



jQuery.fn.textWidth = function() {
    var _t = jQuery(this);
    var html_org = _t.html();
    if (_t[0].nodeName == 'INPUT') {
        html_org = _t.val();
    }
    var html_calcS = '<span class="forcalc">' + html_org + '</span>';
    jQuery('body').append(html_calcS);
    var _lastspan = jQuery('span.forcalc').last();
    //console.log(_lastspan, html_calc);
    _lastspan.css({
        'font-size': _t.css('font-size'),
        'font-family': _t.css('font-family')
    })
    var width = _lastspan.width();
    //_t.html(html_org);
    _lastspan.remove();
    return width;
};

window.requestAnimFrame = (function() {
    //console.log(callback);
    return window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function( /* function */ callback, /* DOMElement */ element) {
            window.setTimeout(callback, 1000 / 60);
        };
})();






var MD5 = function(string) {
    //==== snippet from http://css-tricks.com/ - author unknown
    function RotateLeft(lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    }

    function AddUnsigned(lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    }

    function F(x, y, z) {
        return (x & y) | ((~x) & z);
    }

    function G(x, y, z) {
        return (x & z) | (y & (~z));
    }

    function H(x, y, z) {
        return (x ^ y ^ z);
    }

    function I(x, y, z) {
        return (y ^ (x | (~z)));
    }

    function FF(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function GG(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function HH(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function II(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function ConvertToWordArray(string) {
        var lWordCount;
        var lMessageLength = string.length;
        var lNumberOfWords_temp1 = lMessageLength + 8;
        var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
        var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
        var lWordArray = Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
    };

    function WordToHex(lValue) {
        var WordToHexValue = "",
            WordToHexValue_temp = "",
            lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            WordToHexValue_temp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length - 2, 2);
        }
        return WordToHexValue;
    };

    function Utf8Encode(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    };

    var x = Array();
    var k, AA, BB, CC, DD, a, b, c, d;
    var S11 = 7,
        S12 = 12,
        S13 = 17,
        S14 = 22;
    var S21 = 5,
        S22 = 9,
        S23 = 14,
        S24 = 20;
    var S31 = 4,
        S32 = 11,
        S33 = 16,
        S34 = 23;
    var S41 = 6,
        S42 = 10,
        S43 = 15,
        S44 = 21;

    string = Utf8Encode(string);

    x = ConvertToWordArray(string);

    a = 0x67452301;
    b = 0xEFCDAB89;
    c = 0x98BADCFE;
    d = 0x10325476;

    for (k = 0; k < x.length; k += 16) {
        AA = a;
        BB = b;
        CC = c;
        DD = d;
        a = FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
        d = FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
        c = FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
        b = FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
        a = FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
        d = FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
        c = FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
        b = FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
        a = FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
        d = FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
        c = FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
        b = FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
        a = FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
        d = FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
        c = FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
        b = FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
        a = GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
        d = GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
        c = GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
        b = GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
        a = GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
        d = GG(d, a, b, c, x[k + 10], S22, 0x2441453);
        c = GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
        b = GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
        a = GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
        d = GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
        c = GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
        b = GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
        a = GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
        d = GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
        c = GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
        b = GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
        a = HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
        d = HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
        c = HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
        b = HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
        a = HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
        d = HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
        c = HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
        b = HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
        a = HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
        d = HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
        c = HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
        b = HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
        a = HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
        d = HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
        c = HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
        b = HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
        a = II(a, b, c, d, x[k + 0], S41, 0xF4292244);
        d = II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
        c = II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
        b = II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
        a = II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
        d = II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
        c = II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
        b = II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
        a = II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
        d = II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
        c = II(c, d, a, b, x[k + 6], S43, 0xA3014314);
        b = II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
        a = II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
        d = II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
        c = II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
        b = II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
        a = AddUnsigned(a, AA);
        b = AddUnsigned(b, BB);
        c = AddUnsigned(c, CC);
        d = AddUnsigned(d, DD);
    }

    var temp = WordToHex(a) + WordToHex(b) + WordToHex(c) + WordToHex(d);

    return temp.toLowerCase();
};


window.dzs_open_social_link = function(arg,argthis){
    var leftPosition, topPosition;
    var w = 500, h= 500;
    //Allow for borders.
    leftPosition = (window.screen.width / 2) - ((w / 2) + 10);
    //Allow for title and status bars.
    topPosition = (window.screen.height / 2) - ((h / 2) + 50);
    var windowFeatures = "status=no,height=" + h + ",width=" + w + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";


    arg = arg.replace('{{replaceurl}}',encodeURIComponent(window.location.href));

    var aux = window.location.href;


    var auxa = aux.split('?');

    var cid = '';
    var cid_gallery = '';

    if(argthis){
        if(jQuery(argthis).parent().parent().attr('data-menu-index')){

            cid = jQuery(argthis).parent().parent().attr('data-menu-index');
        }
        if(jQuery(argthis).parent().parent().attr('data-gallery-id')){

            cid_gallery = jQuery(argthis).parent().parent().attr('data-gallery-id');
        }
    }

    arg = arg.replace('{{shareurl}}',encodeURIComponent(auxa[0]+'?audiogallery_startitem_'+cid_gallery+'='+cid));

    //console.info(argthis);
    //console.info('arg - ',arg);
    window.open(arg,"sharer", windowFeatures);
}


function formatTime(arg) {
    //formats the time
    var s = Math.round(arg);
    var m = 0;
    if (s > 0) {
        while (s > 59) {
            m++;
            s -= 60;
        }
        return String((m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s);
    } else {
        return "00:00";
    }
}



function clean_string(arg){

    arg = arg.replace(/[^A-Za-z0-9\-]/g, '');
    //console.info(arg);
    arg = arg.replace(/\./g, '');


    //console.info(arg);

    return arg;


}

function get_query_arg(purl, key){
    if(purl.indexOf(key+'=')>-1){
        //faconsole.log('testtt');
        var regexS = "[?&]"+key + "=.+";
        var regex = new RegExp(regexS);
        var regtest = regex.exec(purl);
        //console.info(regtest);

        if(regtest != null){
            var splitterS = regtest[0];
            if(splitterS.indexOf('&')>-1){
                var aux = splitterS.split('&');
                splitterS = aux[1];
            }
            //console.log(splitterS);
            var splitter = splitterS.split('=');
            //console.log(splitter[1]);
            //var tempNr = ;

            return splitter[1];

        }
        //$('.zoombox').eq
    }
}

function add_query_arg(purl, key,value){
    key = encodeURIComponent(key); value = encodeURIComponent(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

    s = s.replace(r,"$1"+pair);
    //console.log(s, pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }
    //if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};


    //if value NaN we remove this field from the url
    if(value=='NaN'){
        var regex_attr = new RegExp('[\?|\&]'+key+'='+value);
        s=s.replace(regex_attr, '');
    }

    return s;
}
function can_history_api() {
    return !!(window.history && history.pushState);
}
// function ColorLuminance(hex, lum) {
//
//     // validate hex string
//     hex = String(hex).replace(/[^0-9a-f]/gi, '');
//     if (hex.length < 6) {
//         hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
//     }
//     lum = lum || 0;
//
//     // convert to decimal and change luminosity
//     var rgb = "#", c, i;
//     for (i = 0; i < 3; i++) {
//         c = parseInt(hex.substr(i*2,2), 16);
//         c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
//         rgb += ("00"+c).substr(c.length);
//     }
//
//     return rgb;
// }
