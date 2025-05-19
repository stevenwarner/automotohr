<?php
$creds = getCreds('AHR');
?>
<style>
    body {
        margin: 0px;
    }
    .interview-container {
        font-family: "Open Sans", sans-serif;
        min-height: 100vh;
        background-color: #3554DC;
        color: #fff;
    }
    .header {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        max-width: calc(100vw - 200px);
        margin: auto;
        padding: 60px 0;
        font-size: 20px;
    }
    .logo-wrapper,
    .timer-wrapper {
        width: 240px;
    }
    .logo-wrapper {
        margin-right: auto;
    }
    .header .title-wrapper {
        max-width: 774px;
        width: max-content;
        margin: auto;
        display: flex;
        justify-content: center;
    }
    .header .page-title {
        background-color: #FFFFFF1A;
        height: 50px;
        padding: 0 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: max-content;
        border-radius: 100px;
    }
    .timer-wrapper {
        margin-left: auto;
    }
    .header .timer {
        display: block;
        background-color: #FFFFFF4D;
        height: 50px;
        padding: 0px 21px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: max-content;
        border-radius: 100px;
    }
    /* Header Style Ended */

    /* Main Style Started */
    .main {
        height: calc(100vh - 173px);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .conversation {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 120px;
    }
    .conversation .bot,
    .conversation .applicant {
        width: 327px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .conversation .icon-wrapper {
        position: relative;
        width: 327px;
        height: 292px;
        display: flex;
        justify-content: center;
    }

    .frequency {
        position: absolute;
        transform: translateY(-35px);
    }
    .frequency-reverse {
        position: absolute;
        rotate: 180deg;
        bottom: 0;
    }

    .applicant .person-icon {
        border: 6px solid #001055;
        border-radius: 100%;
        z-index: 1;
    }
    .bot .icon {
        width: 250px;
        height: 250px;
        background-color: #fff;
        color: #3554DC;
        font-weight: 700;
        line-height: 40px;
        border-radius: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 128px;
    }

    .caller-title {
        font-weight: 400;
        font-size: 40px;
        line-height: 40px;
        margin-top: 10px;
        text-wrap: nowrap;
    }
    
    .button-wrapper {
        margin-top: 140px;
    }

    button.end-call {
        margin-top: 70px;
        background-color: #FF4343;
        width: 87px;
        height: 46px;
        border: none;
        border-radius: 100px;
        box-shadow: 0px 4px 4px 0px #00000040;
        cursor: pointer;
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />

<div class="interview-container">
    <div class="header">
        <div class="logo-wrapper">
            <svg width="91" height="53" viewBox="0 0 91 53" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <mask id="mask0_1891_191" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="91" height="53">
                    <rect x="0.740723" y="0.233643" width="90" height="51.8549" fill="url(#pattern0_1891_191)"/>
                </mask>
                <g mask="url(#mask0_1891_191)">
                    <rect x="0.740723" y="0.233643" width="90" height="51.8549" fill="white"/>
                </g>
                <defs>
                    <pattern id="pattern0_1891_191" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_1891_191" transform="matrix(0.00724638 0 0 0.0125769 0 -0.00307568)"/>
                    </pattern>
                    <image id="image0_1891_191" width="138" height="80" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"/>
                </defs>
            </svg>
        </div>
        <div class="title-wrapper">
            <div class="page-title">
                <span>Interviewing for job "Software Engineer II"</span>
            </div>
        </div>

        <div class="timer-wrapper">
            <span class="timer">Total Call Time: 02:10</span>
        </div>
    </div>

    <div class="main">
        <div class="conversation">
            <div class="applicant">
                <div class="icon-wrapper">
                    <svg width="327" height="164" viewBox="0 0 327 164" fill="none" xmlns="http://www.w3.org/2000/svg" class="frequency">
                        <path d="M35.8867 156.693L35.6273 161.565L9.13867 161.103L9.4269 155.223L35.8867 156.693Z" fill="white"/>
                        <path d="M36.5207 148.391L36.146 150.813L36.0307 153.263L0.750977 150.351L0.923916 147.238L1.38509 144.125L36.5207 148.391Z" fill="white"/>
                        <path d="M37.7889 140.176L37.0684 145.019L16.9497 142.021L17.8144 136.428L37.7889 140.176Z" fill="white"/>
                        <path d="M39.6626 132.105L38.452 136.832L25.2798 133.978L26.6345 128.732L39.6626 132.105Z" fill="white"/>
                        <path d="M41.9104 124.12C41.2763 125.619 40.8728 127.204 40.4981 128.79L12.626 120.805C13.116 118.874 13.606 116.913 14.3554 115.069L41.9104 124.149V124.12Z" fill="white"/>
                        <path d="M44.764 116.308L43.0922 120.891L15.4219 110.946L17.4683 105.297L44.764 116.308Z" fill="white"/>
                        <path d="M48.367 108.41L46.2341 112.791L16.1426 99.5603L18.8231 94.0258L48.367 108.41Z" fill="white"/>
                        <path d="M52.2004 101.03L50.9322 103.106C50.5575 103.826 50.2404 104.576 49.8657 105.297L14.4419 86.8481C14.9319 85.8968 15.3642 84.9168 15.8542 83.9655L17.4972 81.2271L52.2004 101.002V101.03Z" fill="white"/>
                        <path d="M56.4952 93.9105L53.9876 98.0902L18.9097 76.8168L22.282 71.311L56.4952 93.9105Z" fill="white"/>
                        <path d="M61.3951 87.194L58.4552 91.1143L41.0459 78.921L44.4759 74.3376L61.3951 87.194Z" fill="white"/>
                        <path d="M66.6121 80.7082C65.5168 81.9188 64.3639 83.0719 63.4416 84.4267L43.9282 68.7743C45.0523 67.16 46.407 65.7476 47.7041 64.3063L66.6121 80.6793V80.7082Z" fill="white"/>
                        <path d="M72.1751 74.5394L68.9181 78.1715L45.7153 56.9557L49.8371 52.4589L72.1751 74.5394Z" fill="white"/>
                        <path d="M78.6025 68.6302L74.9708 71.9163L60.3862 56.6386L64.5944 52.8048L78.6025 68.6302Z" fill="white"/>
                        <path d="M85.0014 63.3551C83.7331 64.364 82.3208 65.2287 81.139 66.3241L72.7515 56.2927C74.0773 55.082 75.605 54.102 77.0173 53.0066L85.0302 63.3551H85.0014Z" fill="white"/>
                        <path d="M91.6598 58.3394L87.7398 61.2796L73.1553 41.3322L77.8246 37.8442L91.6598 58.3394Z" fill="white"/>
                        <path d="M98.7791 54.0444L94.5997 56.581L77.1616 29.1677L82.4075 25.968L98.7791 54.0444Z" fill="white"/>
                        <path d="M106.071 50.124C104.601 50.8447 103.103 51.4788 101.69 52.2571L83.8198 19.6263C85.6645 18.6174 87.5957 17.7526 89.4692 16.8302L106.043 50.124H106.071Z" fill="white"/>
                        <path d="M113.565 46.5496C112.066 47.1838 110.625 47.9621 109.155 48.6539L94.7148 17.5509C96.5884 16.6861 98.433 15.706 100.335 14.9277L113.565 46.5496Z" fill="white"/>
                        <path d="M121.751 43.5517L117.168 45.2524L111.778 31.2143L116.909 29.3406L121.751 43.5517Z" fill="white"/>
                        <path d="M129.706 41.188L124.979 42.3987L122.645 34.8464L127.66 33.5492L129.706 41.188Z" fill="white"/>
                        <path d="M137.748 39.1702C136.134 39.4008 134.578 39.9485 133.021 40.3232L127.112 15.5907C129.015 15.1583 130.888 14.5241 132.819 14.2358L137.748 39.1702Z" fill="white"/>
                        <path d="M145.963 37.9307L141.149 38.6802L137.46 17.0608L143.138 16.196L145.963 37.9307Z" fill="white"/>
                        <path d="M154.235 37.1524C152.621 37.2389 150.978 37.2677 149.335 37.4118L146.078 5.93404C148.096 5.76109 150.142 5.70344 152.16 5.58813L154.206 37.1812L154.235 37.1524Z" fill="white"/>
                        <path d="M162.565 36.72L157.665 36.9794L156.281 0.572438L162.565 0.28418V36.72Z" fill="white"/>
                        <path d="M169.857 37.0947L164.957 36.8353L165.447 10.3732L171.327 10.6614L169.857 37.0947Z" fill="white"/>
                        <path d="M178.129 37.7577L175.708 37.383L173.287 37.2677L176.198 1.98486L179.311 2.15782L182.395 2.61903L178.129 37.7577Z" fill="white"/>
                        <path d="M186.344 39.026L181.53 38.3054L184.528 18.1849L190.12 19.0497L186.344 39.026Z" fill="white"/>
                        <path d="M194.443 40.8997L189.716 39.689L192.57 26.5156L197.787 27.8704L194.443 40.8997Z" fill="white"/>
                        <path d="M202.427 43.1769C200.928 42.5427 199.343 42.1392 197.758 41.7645L205.771 13.8899C207.731 14.3511 209.662 14.87 211.507 15.6194L202.427 43.1769Z" fill="white"/>
                        <path d="M210.209 46.0307L205.626 44.3588L215.599 16.6572L221.249 18.7327L210.209 46.0307Z" fill="white"/>
                        <path d="M218.136 49.6339L213.726 47.5008L226.956 17.4066L232.519 20.0586L218.136 49.6339Z" fill="white"/>
                        <path d="M225.486 53.439C224.103 52.5454 222.719 51.7382 221.22 51.0752L239.667 15.6483C241.627 16.5131 243.472 17.6085 245.288 18.7327L225.486 53.4101V53.439Z" fill="white"/>
                        <path d="M232.605 57.7628L228.426 55.2261L249.697 20.174L255.231 23.5466L232.605 57.7628Z" fill="white"/>
                        <path d="M239.321 62.6632L235.401 59.7229L247.593 42.3121L252.176 45.7136L239.321 62.6632Z" fill="white"/>
                        <path d="M245.806 67.8518C244.596 66.7564 243.443 65.6034 242.088 64.681L257.739 45.1659C259.353 46.2613 260.737 47.6449 262.207 48.9421L245.806 67.823V67.8518Z" fill="white"/>
                        <path d="M251.974 73.444L248.343 70.1867L269.557 47.0107L274.053 51.104L251.974 73.444Z" fill="white"/>
                        <path d="M257.854 79.8722L254.568 76.2402L269.874 61.6543L273.707 65.8917L257.854 79.8722Z" fill="white"/>
                        <path d="M263.158 86.2715C262.149 85.0031 261.284 83.5907 260.189 82.4088L270.219 74.0205C271.43 75.3465 272.41 76.8743 273.505 78.2867L263.158 86.3003V86.2715Z" fill="white"/>
                        <path d="M268.145 92.9302L265.205 89.0099L285.179 74.4241L288.667 79.1227L268.145 92.9302Z" fill="white"/>
                        <path d="M272.439 100.05L269.902 95.8705L297.342 78.4597L300.541 83.6772L272.439 100.05Z" fill="white"/>
                        <path d="M276.359 107.372C275.638 105.902 275.004 104.403 274.226 102.991L306.854 85.1473C307.834 86.9922 308.728 88.9235 309.65 90.826L276.359 107.401V107.372Z" fill="white"/>
                        <path d="M279.962 114.867C279.357 113.368 278.55 111.927 277.887 110.456L308.987 96.0146C309.852 97.8883 310.832 99.7332 311.61 101.636L279.991 114.867H279.962Z" fill="white"/>
                        <path d="M282.931 123.024L281.23 118.441L295.267 113.08L297.169 118.211L282.931 123.024Z" fill="white"/>
                        <path d="M285.294 130.98L284.083 126.253L291.635 123.918L292.932 128.934L285.294 130.98Z" fill="white"/>
                        <path d="M287.283 139.052C287.052 137.437 286.505 135.881 286.13 134.324L310.86 128.444C311.293 130.346 311.927 132.22 312.215 134.151L287.283 139.08V139.052Z" fill="white"/>
                        <path d="M288.522 147.267L287.802 142.424L309.419 138.763L310.284 144.413L288.522 147.267Z" fill="white"/>
                        <path d="M289.3 155.54L289.07 150.64L320.545 147.411L320.891 153.493L289.3 155.54Z" fill="white"/>
                        <path d="M289.762 163.871L289.474 158.97L325.877 157.587L326.194 163.871H289.762Z" fill="white"/>
                    </svg>
                    <svg width="327" height="164" viewBox="0 0 327 164" fill="none" xmlns="http://www.w3.org/2000/svg" class="frequency-reverse">
                        <path d="M35.8867 156.693L35.6273 161.565L9.13867 161.103L9.4269 155.223L35.8867 156.693Z" fill="white"/>
                        <path d="M36.5207 148.391L36.146 150.813L36.0307 153.263L0.750977 150.351L0.923916 147.238L1.38509 144.125L36.5207 148.391Z" fill="white"/>
                        <path d="M37.7889 140.176L37.0684 145.019L16.9497 142.021L17.8144 136.428L37.7889 140.176Z" fill="white"/>
                        <path d="M39.6626 132.105L38.452 136.832L25.2798 133.978L26.6345 128.732L39.6626 132.105Z" fill="white"/>
                        <path d="M41.9104 124.12C41.2763 125.619 40.8728 127.204 40.4981 128.79L12.626 120.805C13.116 118.874 13.606 116.913 14.3554 115.069L41.9104 124.149V124.12Z" fill="white"/>
                        <path d="M44.764 116.308L43.0922 120.891L15.4219 110.946L17.4683 105.297L44.764 116.308Z" fill="white"/>
                        <path d="M48.367 108.41L46.2341 112.791L16.1426 99.5603L18.8231 94.0258L48.367 108.41Z" fill="white"/>
                        <path d="M52.2004 101.03L50.9322 103.106C50.5575 103.826 50.2404 104.576 49.8657 105.297L14.4419 86.8481C14.9319 85.8968 15.3642 84.9168 15.8542 83.9655L17.4972 81.2271L52.2004 101.002V101.03Z" fill="white"/>
                        <path d="M56.4952 93.9105L53.9876 98.0902L18.9097 76.8168L22.282 71.311L56.4952 93.9105Z" fill="white"/>
                        <path d="M61.3951 87.194L58.4552 91.1143L41.0459 78.921L44.4759 74.3376L61.3951 87.194Z" fill="white"/>
                        <path d="M66.6121 80.7082C65.5168 81.9188 64.3639 83.0719 63.4416 84.4267L43.9282 68.7743C45.0523 67.16 46.407 65.7476 47.7041 64.3063L66.6121 80.6793V80.7082Z" fill="white"/>
                        <path d="M72.1751 74.5394L68.9181 78.1715L45.7153 56.9557L49.8371 52.4589L72.1751 74.5394Z" fill="white"/>
                        <path d="M78.6025 68.6302L74.9708 71.9163L60.3862 56.6386L64.5944 52.8048L78.6025 68.6302Z" fill="white"/>
                        <path d="M85.0014 63.3551C83.7331 64.364 82.3208 65.2287 81.139 66.3241L72.7515 56.2927C74.0773 55.082 75.605 54.102 77.0173 53.0066L85.0302 63.3551H85.0014Z" fill="white"/>
                        <path d="M91.6598 58.3394L87.7398 61.2796L73.1553 41.3322L77.8246 37.8442L91.6598 58.3394Z" fill="white"/>
                        <path d="M98.7791 54.0444L94.5997 56.581L77.1616 29.1677L82.4075 25.968L98.7791 54.0444Z" fill="white"/>
                        <path d="M106.071 50.124C104.601 50.8447 103.103 51.4788 101.69 52.2571L83.8198 19.6263C85.6645 18.6174 87.5957 17.7526 89.4692 16.8302L106.043 50.124H106.071Z" fill="white"/>
                        <path d="M113.565 46.5496C112.066 47.1838 110.625 47.9621 109.155 48.6539L94.7148 17.5509C96.5884 16.6861 98.433 15.706 100.335 14.9277L113.565 46.5496Z" fill="white"/>
                        <path d="M121.751 43.5517L117.168 45.2524L111.778 31.2143L116.909 29.3406L121.751 43.5517Z" fill="white"/>
                        <path d="M129.706 41.188L124.979 42.3987L122.645 34.8464L127.66 33.5492L129.706 41.188Z" fill="white"/>
                        <path d="M137.748 39.1702C136.134 39.4008 134.578 39.9485 133.021 40.3232L127.112 15.5907C129.015 15.1583 130.888 14.5241 132.819 14.2358L137.748 39.1702Z" fill="white"/>
                        <path d="M145.963 37.9307L141.149 38.6802L137.46 17.0608L143.138 16.196L145.963 37.9307Z" fill="white"/>
                        <path d="M154.235 37.1524C152.621 37.2389 150.978 37.2677 149.335 37.4118L146.078 5.93404C148.096 5.76109 150.142 5.70344 152.16 5.58813L154.206 37.1812L154.235 37.1524Z" fill="white"/>
                        <path d="M162.565 36.72L157.665 36.9794L156.281 0.572438L162.565 0.28418V36.72Z" fill="white"/>
                        <path d="M169.857 37.0947L164.957 36.8353L165.447 10.3732L171.327 10.6614L169.857 37.0947Z" fill="white"/>
                        <path d="M178.129 37.7577L175.708 37.383L173.287 37.2677L176.198 1.98486L179.311 2.15782L182.395 2.61903L178.129 37.7577Z" fill="white"/>
                        <path d="M186.344 39.026L181.53 38.3054L184.528 18.1849L190.12 19.0497L186.344 39.026Z" fill="white"/>
                        <path d="M194.443 40.8997L189.716 39.689L192.57 26.5156L197.787 27.8704L194.443 40.8997Z" fill="white"/>
                        <path d="M202.427 43.1769C200.928 42.5427 199.343 42.1392 197.758 41.7645L205.771 13.8899C207.731 14.3511 209.662 14.87 211.507 15.6194L202.427 43.1769Z" fill="white"/>
                        <path d="M210.209 46.0307L205.626 44.3588L215.599 16.6572L221.249 18.7327L210.209 46.0307Z" fill="white"/>
                        <path d="M218.136 49.6339L213.726 47.5008L226.956 17.4066L232.519 20.0586L218.136 49.6339Z" fill="white"/>
                        <path d="M225.486 53.439C224.103 52.5454 222.719 51.7382 221.22 51.0752L239.667 15.6483C241.627 16.5131 243.472 17.6085 245.288 18.7327L225.486 53.4101V53.439Z" fill="white"/>
                        <path d="M232.605 57.7628L228.426 55.2261L249.697 20.174L255.231 23.5466L232.605 57.7628Z" fill="white"/>
                        <path d="M239.321 62.6632L235.401 59.7229L247.593 42.3121L252.176 45.7136L239.321 62.6632Z" fill="white"/>
                        <path d="M245.806 67.8518C244.596 66.7564 243.443 65.6034 242.088 64.681L257.739 45.1659C259.353 46.2613 260.737 47.6449 262.207 48.9421L245.806 67.823V67.8518Z" fill="white"/>
                        <path d="M251.974 73.444L248.343 70.1867L269.557 47.0107L274.053 51.104L251.974 73.444Z" fill="white"/>
                        <path d="M257.854 79.8722L254.568 76.2402L269.874 61.6543L273.707 65.8917L257.854 79.8722Z" fill="white"/>
                        <path d="M263.158 86.2715C262.149 85.0031 261.284 83.5907 260.189 82.4088L270.219 74.0205C271.43 75.3465 272.41 76.8743 273.505 78.2867L263.158 86.3003V86.2715Z" fill="white"/>
                        <path d="M268.145 92.9302L265.205 89.0099L285.179 74.4241L288.667 79.1227L268.145 92.9302Z" fill="white"/>
                        <path d="M272.439 100.05L269.902 95.8705L297.342 78.4597L300.541 83.6772L272.439 100.05Z" fill="white"/>
                        <path d="M276.359 107.372C275.638 105.902 275.004 104.403 274.226 102.991L306.854 85.1473C307.834 86.9922 308.728 88.9235 309.65 90.826L276.359 107.401V107.372Z" fill="white"/>
                        <path d="M279.962 114.867C279.357 113.368 278.55 111.927 277.887 110.456L308.987 96.0146C309.852 97.8883 310.832 99.7332 311.61 101.636L279.991 114.867H279.962Z" fill="white"/>
                        <path d="M282.931 123.024L281.23 118.441L295.267 113.08L297.169 118.211L282.931 123.024Z" fill="white"/>
                        <path d="M285.294 130.98L284.083 126.253L291.635 123.918L292.932 128.934L285.294 130.98Z" fill="white"/>
                        <path d="M287.283 139.052C287.052 137.437 286.505 135.881 286.13 134.324L310.86 128.444C311.293 130.346 311.927 132.22 312.215 134.151L287.283 139.08V139.052Z" fill="white"/>
                        <path d="M288.522 147.267L287.802 142.424L309.419 138.763L310.284 144.413L288.522 147.267Z" fill="white"/>
                        <path d="M289.3 155.54L289.07 150.64L320.545 147.411L320.891 153.493L289.3 155.54Z" fill="white"/>
                        <path d="M289.762 163.871L289.474 158.97L325.877 157.587L326.194 163.871H289.762Z" fill="white"/>
                    </svg>
            
                    <svg width="244" height="244" viewBox="0 0 244 244" fill="none" xmlns="http://www.w3.org/2000/svg" class="person-icon">
                        <g clip-path="url(#clip0_1904_1055)">
                        <path d="M0 122C0 154.744 12.9004 184.475 33.8956 206.388C56.1018 229.568 87.366 244 122 244C156.634 244 187.898 229.568 210.104 206.388C231.1 184.475 244 154.744 244 122C244 54.622 189.378 0 122 0C54.622 0 0 54.622 0 122Z" fill="url(#paint0_radial_1904_1055)"/>
                        <path d="M122 128.548C94.4738 128.548 72.1594 106.234 72.1594 78.7074C72.1594 51.1811 94.4738 28.8667 122 28.8667C149.526 28.8667 171.841 51.1811 171.841 78.7074C171.841 106.234 149.526 128.548 122 128.548Z" fill="url(#paint1_radial_1904_1055)"/>
                        <path d="M33.8958 206.388C56.1019 229.568 87.3661 244 122 244C156.634 244 187.898 229.568 210.104 206.388C197.564 169.784 162.854 143.471 122 143.471C81.1461 143.471 46.4361 169.784 33.8958 206.388Z" fill="url(#paint2_radial_1904_1055)"/>
                        </g>
                        <defs>
                        <radialGradient id="paint0_radial_1904_1055" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(176.318 66.9329) rotate(180) scale(191.017)">
                        <stop stop-color="#63DFFC"/>
                        <stop offset="1" stop-color="#3F7CD1"/>
                        </radialGradient>
                        <radialGradient id="paint1_radial_1904_1055" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(133.538 61.1504) rotate(180) scale(73.0116 73.0116)">
                        <stop stop-color="white"/>
                        <stop offset="1" stop-color="#D1D1D1"/>
                        </radialGradient>
                        <radialGradient id="paint2_radial_1904_1055" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(142.397 176.029) rotate(180) scale(102.547 102.547)">
                        <stop stop-color="white"/>
                        <stop offset="1" stop-color="#D1D1D1"/>
                        </radialGradient>
                        <clipPath id="clip0_1904_1055">
                        <rect width="244" height="244" fill="white" transform="matrix(-1 0 0 1 244 0)"/>
                        </clipPath>
                        </defs>
                    </svg>
                </div>
                <span class="caller-title">Herry</span>
            </div>
            <div class="bot">
                <div class="icon-wrapper">
                    <span class="icon">A</span>
                </div>
                <span class="caller-title">Alex (AI Interviewer)</span>
            </div>
        </div>

        <div class="button-wrapper">
            <button class="end-call">
                <svg width="38" height="15" viewBox="0 0 38 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.30351 13.643L1.32627 10.6657C1.04876 10.3905 0.831798 10.0603 0.689284 9.69631C0.546767 9.33234 0.481838 8.94262 0.498655 8.55211C0.51547 8.16159 0.613658 7.77889 0.786935 7.42853C0.960213 7.07816 1.20476 6.76784 1.5049 6.51745C4.89055 3.79545 8.86748 1.90556 13.1161 0.999626C17.0377 0.125472 21.1036 0.125474 25.0251 0.999627C29.2913 1.91143 33.2827 3.81535 36.676 6.55714C36.9753 6.80678 37.2193 7.11599 37.3924 7.46508C37.5656 7.81417 37.6642 8.1955 37.682 8.58479C37.6997 8.97408 37.6362 9.36279 37.4955 9.72619C37.3547 10.0896 37.1399 10.4197 36.8646 10.6955L33.8874 13.6727C33.4105 14.1591 32.773 14.4555 32.0938 14.5066C31.4145 14.5577 30.7399 14.3601 30.1956 13.9506C29.1171 13.1235 27.9486 12.421 26.7122 11.8566C26.2235 11.6349 25.8085 11.2778 25.5166 10.8275C25.2246 10.3772 25.0678 9.8527 25.0648 9.31605V6.79532C21.1675 5.72353 17.0531 5.72353 13.1558 6.79532L13.1558 9.31605C13.1528 9.8527 12.9961 10.3772 12.7041 10.8275C12.4121 11.2778 11.9972 11.6349 11.5084 11.8566C10.2721 12.421 9.1035 13.1235 8.02506 13.9506C7.47497 14.3647 6.79188 14.5621 6.10571 14.5054C5.41953 14.4487 4.77814 14.1418 4.30351 13.643Z" fill="white"/>
                </svg>
            </button>
        </div>
    </div>
</div>
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script>
    const job_list_sid = `<?php echo $portal_job_list["sid"]; ?>`;
    const ServerPath = '<?php echo $creds->API_BROWSER_URL; ?>';
    let socket;
    let interviewStarted = false;
    let mediaRecorder = null;
    let audioStream = null;
    let silenceDetectionInterval = null;
    let chatId = '';
    let audioQueue = [];
    let isPlaying = false;
    let audioContext;
    let nextScheduledTime = 0;
    let scriptProcessor;

    document.addEventListener('DOMContentLoaded', function() {

        // Clear previous audio queue
        audioQueue = [];
        isPlaying = false;
        
        function setupSocketConnection() {
            // Connect to your Node.js server with Socket.IO
            socket = io(ServerPath);
            socket.binaryType = 'arraybuffer';
            
            // Handle connection events
            socket.on('connect', () => {
                console.log('Connected to Socket.IO server');
                startInterview();
            });

            // Handle status updates
            socket.on('status', (data) => {
                if (data.status === 'ready') {
                    // Deepgram connection is ready, setup audio recording
                    console.log(`Status: ${data.status} - ${data.message}`);
                    setupAudioRecording(data.chatId);
                }
                else {
                    console.log(`Status: ${data.status} - ${data.message}`);
                }
            });

            // Handle bot responses
            socket.on('botResponse', (data) => {
                playDeepgramAudio(data.audio, data.format || 'mp3');
                return false;
                try {
                    const message = data;
                
                    switch (message.type) {
                        case 'audio':
                            // Process audio data
                            audioQueue.push(data.data);
                            if(audioQueue.length === 1) {
                                console.log('audio data', data.data)
                                playDeepgramAudio(data.data, 'mp3');
                            }
                        break;
                        
                        case 'info':
                            console.log('Info: ' + message.message);
                        break;
                        
                        case 'error':
                            console.error('Error: ' + message.message);
                        break;
                        
                        default:
                        console.log('Received unknown message type: ' + message.type);
                    }
                } catch (error) {
                    console.error('Error processing message: ' + error.message);
                }
                
                return false;
            });

            // 4. Method
            // socket.on('message', async (data) => {
            //     try {
            //         // Make sure we have a properly initialized audio context first
            //         initAudioContext();
                    
            //         // Handle different message formats
            //         let message;
            //         if (typeof data === 'string') {
            //             try {
            //                 message = JSON.parse(data);
            //             } catch (e) {
            //                 console.error('Failed to parse message as JSON:', e);
            //                 return;
            //             }
            //         } else {
            //             message = data;
            //         }
                    
            //         // Process audio data if available
            //         if (message && message.type === 'audio' && Array.isArray(message.data)) {
            //             // Convert array back to Uint8Array
            //             const uint8Array = new Uint8Array(message.data);
                        
            //             // Process the audio data
            //             processAudioData(uint8Array.buffer);
            //         } else {
            //             console.log('Received non-audio message:', message);
            //         }
            //     } catch (error) {
            //         console.error('Error handling socket message:', error);
            //     }

            // });

            // 5. Method
            socket.on('message', async (data) => {
                try {
                    data = JSON.parse(data);

                    // Step 1: Convert to Uint8Array (byte array)
                    const uint8 = new Uint8Array(data.data.data);

                    // Step 2: Convert Uint8Array to Int16Array (little-endian assumed)
                    const int16Array = new Int16Array(uint8.buffer);
                    const float32Array = convertInt16ToFloat32(int16Array);
                    
                    // Add to queue instead of scheduling immediately
                    audioQueue.push(float32Array);
                    
                    // Start processing if not already playing
                    if (!isPlaying) {
                        processAudioQueue();
                    }
                } catch (error) {
                    console.error('Error processing message: ' + error.message);
                }
            });


            socket.on('speaking', (isSpeaking) => {
                if(isSpeaking && window.currentAudio) {
                    window.currentAudio.pause();
                    window.currentAudio.currentTime = 0;
                    window.currentAudio = null;
                }
            })

            // Handle errors
            socket.on('error', (error) => {
                console.error('Socket.IO error:', error);
            });

            // Handle disconnection
            socket.on('disconnect', () => {
                console.log('Disconnected from Socket.IO server');
                
                // Clear silence detection interval
                if (silenceDetectionInterval) {
                    clearInterval(silenceDetectionInterval);
                    silenceDetectionInterval = null;
                }
                
                // Stop MediaRecorder if running
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }
                
                // Attempt to reconnect if interview is still ongoing
                if (interviewStarted) {
                    console.log('Attempting to reconnect...');
                    setTimeout(setupSocketConnection, 2000);
                }
            });
        }

        // Add audio recording function for Deepgram
        function setupAudioRecording(chatId) {
            console.log('Setting up audio recording...');

            // Request access to the microphone
            navigator.mediaDevices.getUserMedia({
                audio: true
            })
            .then(stream => {
                console.log('Got microphone access!');
                audioStream = stream
                // Store the stream for later use
                window.audioStream = audioStream;

                audioContext = new AudioContext({ sampleRate: 16000 });
                const source = audioContext.createMediaStreamSource(stream);
                scriptProcessor = audioContext.createScriptProcessor(4096, 1, 1);

                scriptProcessor.onaudioprocess = (event) => {
                    const floatData = event.inputBuffer.getChannelData(0);
                    const int16Data = floatToInt16(floatData);
                };
        
                source.connect(scriptProcessor);
                scriptProcessor.connect(audioContext.destination);

                try {
                    // Check supported MIME types
                    const mimeTypes = [
                        'audio/webm',
                        'audio/webm;codecs=opus',
                        'audio/ogg;codecs=opus',
                        'audio/wav',
                        ''  // Empty string means browser's default
                    ];
                    
                    let selectedMimeType = '';
                    for (let type of mimeTypes) {
                        if (MediaRecorder.isTypeSupported(type)) {
                            selectedMimeType = type;
                            console.log('Using MIME type:', selectedMimeType);
                            break;
                        }
                    }
                    
                    // Create the MediaRecorder with the selected MIME type
                    mediaRecorder = new MediaRecorder(stream, {
                        mimeType: selectedMimeType,
                        audioBitsPerSecond: 16000
                    });
                    
                    // Configure ondataavailable handler
                    mediaRecorder.ondataavailable = (event) => {
                        if (event.data.size > 0 && socket && socket.connected) {
                            socket.emit('audioData', event.data);
                        }
                    };
                    
                    mediaRecorder.onerror = (event) => {
                        console.error('MediaRecorder error:', event.error);
                    };
                    
                    // Start recording with shorter time slices (100ms) for more responsive transcription
                    mediaRecorder.start(100);
                    console.log('MediaRecorder started!', mediaRecorder.state);
                    
                    // Keep a reference to the MediaRecorder
                    window.mediaRecorder = mediaRecorder;
                } catch (err) {
                    console.error('Failed to create MediaRecorder:', err);
                    alert('Error creating audio recorder. Your browser may not support this feature.');
                }
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
            });
        }
        setupSocketConnection();


        // Function to play Deepgram audio from base64 data
        function playDeepgramAudio(base64AudioData, format = 'mp3') {
            // Stop any playing audio first
            if (window.currentAudio) {
                window.currentAudio.pause();
                window.currentAudio.currentTime = 0;
                window.currentAudio = null;
            }
            
            try {
                const byteCharacters = atob(base64AudioData);
                const byteArrays = [];
                
                for (let offset = 0; offset < byteCharacters.length; offset += 512) {
                    const slice = byteCharacters.slice(offset, offset + 512);
                    
                    const byteNumbers = new Array(slice.length);
                    for (let i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }
                    
                    const byteArray = new Uint8Array(byteNumbers);
                    byteArrays.push(byteArray);
                }
                
                const audioBlob = new Blob(byteArrays, { type: 'audio/wav' });
                
                // Create audio URL and audio element
                const audioUrl = URL.createObjectURL(audioBlob);
                const audio = new Audio(audioUrl);
                // const audio = new Audio(`data:audio/${format};base64,${base64AudioData}`);
                
                // Store reference to current audio
                window.currentAudio = audio;
                
                // Add event listeners
                window.currentAudio.addEventListener('play', () => {
                    console.log('Bot audio started playing');
                    // You can add UI indicator here that bot is speaking
                    // document.querySelector('.bot .icon-wrapper').classList.add('speaking');
                });
                
                window.currentAudio.addEventListener('ended', () => {
                    console.log('Bot audio finished playing');
                    window.currentAudio = null;
                    
                    // Remove speaking indicator
                    // document.querySelector('.bot .icon-wrapper').classList.remove('speaking');
                    
                    // Clean up the blob URL
                    URL.revokeObjectURL(audioUrl);
                });
                
                window.currentAudio.addEventListener('error', (e) => {
                    console.error('Audio playback error:', e);
                    // document.querySelector('.bot .icon-wrapper').classList.remove('speaking');
                    URL.revokeObjectURL(audioUrl);
                });
                
                // Play the audio
                window.currentAudio.play().catch(err => {
                    console.error('Error playing audio:', err);
                    // document.querySelector('.bot .icon-wrapper').classList.remove('speaking');
                });
            } catch (error) {
                console.error('Error processing or playing audio:', error);
            }
        }

        function stopInterview() {
            interviewStarted = false;
            // recognition.stop();

            // Stop audio recording
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
            }

            // Stop all audio tracks
            if (audioStream) {
                audioStream.getTracks().forEach(track => track.stop());
            }
            
            // Clear silence detection interval
            if (silenceDetectionInterval) {
                clearInterval(silenceDetectionInterval);
                silenceDetectionInterval = null;
            }
        }

        function startInterview() {
            interviewStarted = true;
            try {
                // Tell the server to start speech recognition with Deepgram
                socket.emit('startSpeechRecognition', {job_list_sid});
            } catch (e) {
                console.error('Error starting interview:', e);
            }
        }

        // 5. Method to play audio
        function floatToInt16(floatArray) {
            const int16Array = new Int16Array(floatArray.length);
            for (let i = 0; i < floatArray.length; i++) {
                int16Array[i] = Math.min(32767, Math.max(-32768, floatArray[i] * 32768));
            }
            return int16Array;
        }

        function convertInt16ToFloat32(int16Array) {
            const float32Array = new Float32Array(int16Array.length);
            for (let i = 0; i < int16Array.length; i++) {
                float32Array[i] = int16Array[i] / 32768.0; // Normalize to [-1, 1]
            }
            return float32Array;
        }

        function processAudioQueue() {
            if (audioQueue.length === 0) {
                isPlaying = false;
                return;
            }
    
            isPlaying = true;
            const float32Array = audioQueue.shift();
            const audioBuffer = audioContext.createBuffer(1, float32Array.length, 16000);
            audioBuffer.getChannelData(0).set(float32Array);
    
            const source = audioContext.createBufferSource();
            source.buffer = audioBuffer;
            source.connect(audioContext.destination);
    
            // Calculate precise timing
            const now = audioContext.currentTime;
            const startTime = Math.max(now, nextScheduledTime);
            
            source.start(startTime);
            source.onended = () => {
                // Schedule next chunk immediately after this one finishes
                nextScheduledTime = startTime + audioBuffer.duration;
                processAudioQueue();
            };
        }
        
    });
</script>