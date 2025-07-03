<style>
    .cookie-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }

    .cookie-modal__content {
        background: #fff;
        max-width: 700px;
        width: 100%;
        padding: 24px;
        border-radius: 8px;
        position: relative;
        font-family: 'Segoe UI', sans-serif;
        max-height: 90vh;
        overflow-y: auto;
    }

    .cookie-modal__close {
        position: absolute;
        top: 12px;
        right: 12px;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }

    .cookie-modal h2 {
        margin-bottom: 12px;
    }

    .cookie-modal p {
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .cookie-toggle {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .cookie-toggle input {
        margin-right: 8px;
    }

    .cookie-category {
        border-top: 1px solid #e0e0e0;
        padding-top: 16px;
        margin-top: 16px;
    }

    .cookie-category label {
        font-weight: 600;
        /* display: flex;*/
        align-items: center;
        vertical-align: top;
    }

    .cookie-category input {
        margin-right: 8px;
    }

    .cookie-category__details {
        margin-left: auto;
        font-size: 0.9em;
        color: #0073e6 !important;
        vertical-align: top;
    }

    .cookie-category__desc {
        margin-top: 8px;
        font-size: 0.94em;
        color: #555;
    }

    .cookie-modal__actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 24px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.95em;
    }

    .btn--primary {
        background: #0073e6;
        color: #fff;
        border: none;
    }

    .btn--secondary {
        background: #f0f0f0;
        color: #333;
        border: none;
    }

    .btn--outline {
        background: transparent;
        color: #333;
        border: 1px solid #999;
    }

    .cookiecheckbox {
        width: 8%;
        height: 30px !important;
    }




    #cookie-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #222;
        display: none;
        color: #fff;
        padding: 20px;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
    }
</style>

<div id="cookie-banner">
    <div class="row">
        <div class="col-md-8" style="margin-top: 10px;">
            <span>
                This website uses cookies to enhance your browsing experience, analyze website traffic, and remember
                your preferences. For more details about the cookies we use and how we manage your data, please review
                our <a href="<?php echo base_url('terms-of-use');?>" class="">Privacy Policy</a>.
            </span>
        </div>
        <div class="col-md-4 text-center">
            <button id="accept-cookies" class="btn btn-success">Accept</button>
            <button id="cookies-preferences" class="btn btn-success">Preferences</button>
        </div>
    </div>
</div>





<div id="cookie-modal" class="cookie-modal">
    <div class="cookie-modal__content">
        <button class="cookie-modal__close" onclick="closeModal()">×</button>
        <h2 style="text-align: left;">Preferences</h2>
        <p>
            We use different types of cookies to optimize your experience on our website.
            Click on the categories below to learn more about their purposes.
            You may choose which types of cookies to allow and can change your
            preferences at any time. Remember that disabling cookies may affect your
            experience on the website. You can learn more about how we use cookies by
            visiting our <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy" target="_blank">Cookie Policy</a> and
            <a href="<?php echo base_url('terms-of-use');?>" target="_blank">Privacy Policy</a>.
        </p>

        <div class="cookie-category">
            <input type="checkbox" class="cookiecheckbox" id="toggle-donotsell">
            <label>Do not sell or share my personal information </label>
        </div>

        <div class="cookie-category">
            <input type="checkbox" class="cookiecheckbox" checked disabled>
            <label>Essential Cookies</label>
            <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy#essential" target="_blank" class="cookie-category__details">Details</a>
            <p class="cookie-category__desc">
                These cookies are necessary to the core functionality of our website and
                some of its features, such as access to secure areas.
            </p>
        </div>

        <div class="cookie-category">
            <input type="checkbox" id="toggle-performance" class="cookiecheckbox" checked>
            <label>
                Performance and Functionality Cookies
            </label>
            <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy#performance" target="_blank" class="cookie-category__details">Details</a>
            <p class="cookie-category__desc">
                These cookies are used to enhance the performance and functionality of our
                websites but are nonessential to their use. However, without these cookies,
                certain functionality (like videos) may become unavailable.
            </p>
        </div>

        <div class="cookie-category">
            <input type="checkbox" id="toggle-analytics" class="cookiecheckbox" checked>
            <label>Analytics and Customization Cookies</label>
            <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy#analytics" target="_blank" class="cookie-category__details">Details</a>
            <p class="cookie-category__desc">
                These cookies collect information that can help us understand how our
                websites are being used. This information can also be used to measure
                effectiveness in our marketing campaigns or to curate a personalized site
                experience for you.
            </p>
        </div>

        <div class="cookie-category">
            <input type="checkbox" id="toggle-marketing" class="cookiecheckbox" checked>
            <label>Advertising Cookies</label>
            <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy#advertising" target="_blank" class="cookie-category__details">Details</a>
            <p class="cookie-category__desc">
                These cookies are used to make advertising messages more relevant to you.
                They prevent the same ad from continuously reappearing, ensure that ads
                are properly displayed for advertisers, and in some cases select
                advertisements that are based on your interests.
            </p>
        </div>

        <div class="cookie-category">
            <input type="checkbox" id="toggle-social" class="cookiecheckbox" checked>
            <label>Social networking Cookies</label>
            <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy#social" target="_blank" class="cookie-category__details">Details</a>
            <p class="cookie-category__desc">
                These cookies enable you to share our website's content through
                third-party social networks and other websites. These cookies may also be
                used for advertising purposes.
            </p>
        </div>

        <div class="cookie-category">
            <input type="checkbox" id="toggle-unclassified" class="cookiecheckbox" checked>
            <label>Unclassified Cookies</label>
            <a href="<?php echo MAIN_STORE_FULL_URL_SSL; ?>cookie-policy#unclassified" target="_blank" class="cookie-category__details">Details</a>
            <p class="cookie-category__desc">
                These are cookies that have not yet been categorized. We are in the
                process of classifying these cookies with the help of their providers.
            </p>
        </div>

        <div class="cookie-modal__actions">
            <button onclick="rejectAll()" class="btn btn--outline">Decline All</button>
            <button onclick="acceptAll()" class="btn btn--primary">Allow All</button>
            <button onclick="savePreferences()" class="btn btn--secondary">Save Preferences</button>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            const LS_KEY = 'automotohr_cookie_preferences_ams';
            let getsavedPrefs = [];
            // Utility to set a cookie
            function setCookie(name, value, days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                const expires = "expires=" + date.toUTCString();
                document.cookie = `${name}=${value};${expires};path=/`;

                //             
                ['toggle-performance', 'toggle-analytics', 'toggle-marketing', 'toggle-social', 'toggle-unclassified']
                .forEach(id => document.getElementById(id).checked = true);
                savePreferences();

            }

            function setCookiePref(name, value, days, savepref = true) {

                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                const expires = "expires=" + date.toUTCString();
                document.cookie = `${name}=${value};${expires};path=/`;
                //
                if (savepref == true) {
                    savePreferences();
                }

            }

            // Utility to get a cookie
            function getCookie(name) {

                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let c of ca) {
                    while (c.charAt(0) === ' ') c = c.substring(1);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
                }
                return null;
            }

            // Check cookie on page load
            window.addEventListener("load", function() {
                const cookieConsent = getCookie("cookie_consent");
                if (!cookieConsent) {
                    document.getElementById("cookie-banner").style.display = "flex";
                }

                document.getElementById("accept-cookies").addEventListener("click", function() {
                    setCookie("cookie_consent", "accepted", 365);
                    document.getElementById("cookie-banner").style.display = "none";
                });

                //
                document.getElementById("cookies-preferences").addEventListener("click", function() {
                    modal.style.display = 'flex';
                    const savedPref = JSON.parse(localStorage.getItem(LS_KEY) || 'null');
                    if (savedPref) {
                        saved.analytics ? document.getElementById('toggle-donotsell').checked = true : document.getElementById('toggle-donotsell').checked = false;
                        saved.performance ? document.getElementById('toggle-performance').checked = true : document.getElementById('toggle-performance').checked = false;
                        saved.analytics ? document.getElementById('toggle-analytics').checked = true : document.getElementById('toggle-analytics').checked = false;
                        saved.marketing ? document.getElementById('toggle-marketing').checked = true : document.getElementById('toggle-marketing').checked = false;
                        saved.social ? document.getElementById('toggle-social').checked = true : document.getElementById('toggle-social').checked = false;
                        saved.unclassified ? document.getElementById('toggle-unclassified').checked = true : document.getElementById('toggle-unclassified').checked = false;

                    }
                });
            });
        </script>


        <script>
            const modal = document.getElementById('cookie-modal');

            modal.style.display = 'none';

            // Load saved preferences or show modal
            const saved = JSON.parse(localStorage.getItem(LS_KEY) || 'null');

            console.log(saved);
            if (!saved) {
                // modal.style.display = 'flex';
            } else {

                applyConsent(saved);
                modal.style.display = 'none';

            }
            // Helpers to read toggles
            function readPrefs() {
                return {
                    doNotSell: document.getElementById('toggle-donotsell').checked,
                    performance: document.getElementById('toggle-performance').checked,
                    analytics: document.getElementById('toggle-analytics').checked,
                    marketing: document.getElementById('toggle-marketing').checked,
                    social: document.getElementById('toggle-social').checked,
                    unclassified: document.getElementById('toggle-unclassified').checked,
                    timestamp: new Date().toISOString()
                };
            }
            // Actions
            window.acceptAll = () => {
                ['toggle-performance', 'toggle-analytics', 'toggle-marketing', 'toggle-social', 'toggle-unclassified']
                .forEach(id => document.getElementById(id).checked = true);
                setCookiePref("cookie_consent", "accepted", 365);
                document.getElementById("cookie-banner").style.display = "none";
            };

            window.rejectAll = () => {
                ['toggle-performance', 'toggle-analytics', 'toggle-marketing', 'toggle-social', 'toggle-unclassified']
                .forEach(id => document.getElementById(id).checked = false);
                setCookiePref("cookie_consent", "accepted", 365);
                document.getElementById("cookie-banner").style.display = "none";
            };

            window.savePreferences = () => {
                localStorage.removeItem(LS_KEY);
                const prefs = readPrefs();
                localStorage.setItem(LS_KEY, JSON.stringify(prefs));
                closeModal();
                applyConsent(prefs);
                getsavedPrefs = JSON.parse(localStorage.getItem(LS_KEY) || 'null');
                setCookiePref("cookie_consent", "accepted", 365, false);
                saveCookieLog();
                document.getElementById("cookie-banner").style.display = "none";

            };

            window.closeModal = () => {
                modal.style.display = 'none';
            };

            // Your hook: fire or block scripts based on prefs
            window.applyConsent = (prefs) => {
                // Example: Analytics                  
                // Example: Marketing pixel
                if (prefs.marketing && !prefs.doNotSell) {
                    loadScript('https://connect.facebook.net/en_US/fbevents.js', () => {
                        fbq('init', 'YOUR_PIXEL_ID'); // Replace with your actual Pixel ID
                    });
                }

            };

            // Utility to inject ∂ scripts
            function loadScript(src) {
                const s = document.createElement('script');
                s.src = src;
                s.async = true;
                document.head.appendChild(s);
            }

            //
            const banner = document.getElementById('cookie-banner');
            const gpcEnabled = navigator.globalPrivacyControl === true;

            function getConsent() {
                return JSON.parse(localStorage.getItem('automotohr_cookie_consent') || 'null');
            }

            function showBanner() {
                //  banner.style.display = 'block';
            }

            //
            function saveConsent(acceptAll) {
                const consent = {
                    functional: true,
                    analytics: acceptAll || document.getElementById('consent-analytics').checked,
                    marketing: acceptAll || document.getElementById('consent-marketing').checked,
                    gpc: gpcEnabled,
                    timestamp: new Date().toISOString()
                };

                localStorage.setItem('automotohr_cookie_consent', JSON.stringify(consent));
                banner.style.display = 'none';
                applyConsent(consent);
            }

            //
            function denyAll() {
                const consent = {
                    functional: true,
                    analytics: false,
                    marketing: false,
                    gpc: gpcEnabled,
                    timestamp: new Date().toISOString()
                };
                localStorage.setItem('automotohr_cookie_consent', JSON.stringify(consent));
                banner.style.display = 'none';
                applyConsent(consent);
            }

            //
            function applyConsent(consent) {
                // Block or allow scripts                  
                if (consent.marketing) {
                    //  loadScript('<https://connect.facebook.net/en_US/fbevents.js');
                    // Add Meta Pixel init here
                }
            }

            //
            function loadScript(src) {
                const s = document.createElement('script');
                s.src = src;
                s.async = true;
                document.head.appendChild(s);
            }

            // Check GPC
            if (gpcEnabled) {
                denyAll(); // Auto opt-out under CPRA
            } else {
                const consent = getConsent();
                if (!consent) {
                    showBanner();
                } else {
                    applyConsent(consent);

                }
            }

            //
            function saveCookieLog() {

                var baseURI = '<?php echo base_url(); ?>';
                var userAgent = navigator.userAgent;
                var currentUrl = window.location.href;
                const cookieDataObj = {
                    userAgent: userAgent,
                    currentUrl: currentUrl,
                    prefer: getsavedPrefs
                };

                $.ajax({
                    url: baseURI + "cookie/savecookiedata",
                    method: "POST",
                    data: cookieDataObj,
                })

            }
        </script>