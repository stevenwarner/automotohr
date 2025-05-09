<style>
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
        <div class="col-md-10" style="margin-top: 10px;">
            <span>
                This website uses cookies to enhance your browsing experience, analyze website traffic, and remember
                your preferences. For more details about the cookies we use and how we manage your data, please review
                our <a href="<?= base_url("privacy-policy"); ?>" class="">Privacy Policy</a>.
            </span>
        </div>
        <div class="col-md-2 text-center">
            <button id="accept-cookies" class="btn btn-success">Accept</button>
        </div>
    </div>
</div>


<script>
    // Utility to set a cookie
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = `${name}=${value};${expires};path=/`;
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
    window.addEventListener("load", function () {
        const cookieConsent = getCookie("cookie_consent");
        if (!cookieConsent) {
            document.getElementById("cookie-banner").style.display = "flex";
        }

        document.getElementById("accept-cookies").addEventListener("click", function () {
            setCookie("cookie_consent", "accepted", 365);
            document.getElementById("cookie-banner").style.display = "none";
        });
    });

</script>