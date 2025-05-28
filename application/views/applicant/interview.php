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
        background-color: #0DCAF0;
        color: #fff;
    }
    .header {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        align-items: center;
        max-width: calc(100vw - 200px);
        margin: auto;
        padding: 60px 0;
        font-size: 20px;
    }
    .logo-wrapper,
    .right-section{
        width: 240px;
    }
    .header .title-wrapper {
        max-width: 774px;
        width: max-content;
        display: flex;
        justify-content: center;
    }
    .header .page-title {
        background-color: #FFFFFF4D;
        height: 50px;
        padding: 0 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: max-content;
        border-radius: 100px;
        overflow: hidden;
    }
    .header .right-section > span {
        display: block;
        background-color: #FFFFFF4D;
        height: 50px;
        padding: 0px 21px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 100px;
    }
    /* Header Style Ended */

    /* Main Style Started */
    .main {
        min-height: calc(100vh - 173px);
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
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        max-width: 350px;
        width: 350px;
    }
    .conversation .icon-wrapper .image, .conversation .icon-wrapper svg {
        border: 1px solid #fff;
        background-color: #0D9FBD;
        z-index: 1;
        border-radius: 10px;
        width: 250px;
        height: 250px;
    }
    .conversation .icon-wrapper {
        position: relative;
        width: 250px;
        height: 250px;
        display: flex;
        justify-content: center;
        border-radius: 10px;
    }

    .layer-1, .layer-2, .layer-3 {
        position: absolute;
        background-color: #FFFFFF4D;
        transform: translateY(-50%) scale(0.5) rotate(0deg);
        top: 50%;
        border-radius: 10px;
        will-change: transform;
        transform-origin: center center;
        transition: transform 0.08s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .layer-1 { width: 265px; height: 265px; }
    .layer-2 { width: 280px; height: 280px; }
    .layer-3 { width: 295px; height: 295px; }

    .bot .image {
        width: 250px;
        height: 250px;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .bot .image img {
        position: absolute;
        object-fit: contain;
        inset: 0;
        width: 100%;
    }

    .caller-title {
        font-weight: 400;
        font-size: 40px;
        line-height: 40px;
        margin-top: 49px;
        text-wrap: nowrap;
    }
    
    .button-wrapper {
        margin-top: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .timer-wrapper {
        width: 240px;
    }
    .timer {
        display: block;
        background-color: #FFFFFF4D;
        height: 50px;
        padding: 0px 21px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: max-content;
        border-radius: 100px;
        margin: auto;
    }

    button.end-call {
        margin: 70px 0 40px 0px;
        background-color: #FF4343;
        width: 116px;
        height: 46px;
        border: none;
        border-radius: 100px;
        box-shadow: 0px 4px 4px 0px #00000040;
        cursor: pointer;
    }

    .footer {
        max-width: calc(100vw - 200px);
        width: 100%;
        text-align: right;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 23px;
    }

    .footer p {
        line-height: 22px;
        font-size: 18px;
        font-weight: 600;
    }

    .footer img {
        width: auto;
        height: 50px;
    }

    @media screen and (max-width: 1025px) {
        .header,
        .conversation {
            flex-direction: column;
            align-items: center;
        }
        .header .logo-wrapper,
        .header .title-wrapper,
        .header .timer-wrapper {
            margin: 0;
            width: max-content;
        }

        .conversation {
            margin-top: 70px;
        }
    }

    @media screen and (max-width: 769px) {
        .header {
            max-width: calc(100vw - 50px);
        }
        .header .title-wrapper {
            width: 100%;
            overflow: hidden;
            border-radius: 100px;
        }

        .header .title-wrapper span {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
    }

    /* Microphone Connection Style */
    .microphone-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .microphone-popup-content {
        background-color: rgb(13 202 240 / 30%);
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90%;
    }

    .microphone-popup h3 {
        color: #ffffff;
        margin-bottom: 15px;
        font-size: 24px;
    }

    .microphone-popup p {
        color: #f1f1f1;
        margin-bottom: 25px;
        font-size: 16px;
        line-height: 1.5;
    }

    .microphone-popup button {
        background-color: #0DCAF0;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .microphone-popup button:hover {
        background-color: #0bb5d1;
    }

    .hidden {
        display: none;
    }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
                    <image id="image0_1891_191" width="138" height="80" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIoAAABQCAYAAADGIu0tAAAgAElEQVR4nO29d5wdxZUv/j1VHe69EzXSKAsFRLCwQEKSWRwWCRuLDA7IafHa3rV56+e0fu/tetcYLmIx65/Nssvz2sZp12Hf2oDBVrJxQsZkJI3AIJJQDqOJN/a93V1V5/1RfWfmTtJIiOD9ve/n06C53V1VXX3q1MlN+P8hmNkF4ANoBzAFQCOAZgAtAFwADCACEAAoAigBKAPoA9BFRNGrMOxXFfRqD+DlBjNnACwAcDKA05NjAYAZsESSAuDAEshoMABiAApACKAXQBeA3QD2ANgBYBeAXUTU/bI9yKuM/3KEwsw+gPkAlgJYBWA5gLkA2l7GbiMA3QCeB/AIgPsAPEFEXS9jn68o/ksQSsI1TgVwHoDVAJbAcow6aKUQhiHCKCrGcVxWkerTrEusdU4bU2BGDIImACSEBCCllJ4UsllI0SilzLiuO8l13LTruY2u63pEY07hfgDbAGwC8Fsi2vlyPPsrhT86QmFmgh23A2A6LHG8C8AbYWWOAVQqFQQWh8Iw3B2G4V6lVKfWuo+Zi8aYIitlRnQiJQCAiEgm/xZCsOM4JITIOEQNwnWbHcdp9Txvlud5c3zfn+v7/lTP89KjDLsIy2nuArCOiDpP1Hy8UvijIZSEQFwAHuzWchGANQCWDb2uUCigWCx2l4vFZyph+GwYhi/GcXyQlYo0oLTWtfZYCGGYmYmojliIiIQQgogEACmEkEQkiIgkACElmMi4wjXSk3DIEcIVru+6s1xLMKelU+nTUunUdFmjtEHsB3A3gO8RUcfLMVcvB17zhJIQiA8gDUsg7wRwFexWAwAolUrI5XJ9xUKxI6gEHdVq9VkVqoJWYdUwGwI0M1cJCBVRSESR67qxMSaWUhohhB7ap+M4QiklfN93mNlhZkdr7QkhPGOML6X0SZMHBy4A1xVCkuMQEWnf95VDJKXnpX3fn5dOp89Ip9NLMpnMyaif7xDALwHcDmATEfHLO5MvDa9pQkkE0wyAmQAuBvBhAK+rne/p6UEul3s+n8//vlouPx6GYRcbUwGgNFEgjSnBdUvGmKoQIkylUlEcx3FTU5PetWuXAYCrrrqKYdXh4SDcCdrcvpmmTp0q8vm8bGtrc6IociggL/biFDNnlFINRNRARBkhRNpxHM9xHCmlhO/7ynVdnUql0r7vn+z7/nmu654FYNKwvn4L4B+J6FcvwzSeELwmCYWZHQANsHaNtwD4KKwsAmMMurq60N/b/2ShULi3XCl3aK17SeuIpSwZY3K+1qUKUQAgLJVK6qqrrjInesUyM23evFmedtppXk9Pj+/7fgZAk+u6zb7vN0spG1zXTTmO40opWQgRSymNQzQVUr5BCHEegHnDmv05gP+PiDafyLGeCLzmCCXRYFphVdr3AfgLWK6Crq4u9PT0PNff339PuVx+lJnzQoiiMSZnjMlrrcuVSiVcs2aNHqeLl2vcBEDCbpMZWCKflBxNye9Ca62jKIqMMY1EtNxxnEs8z5s/pCkD4IcA/oGIXnhln2JsvGYIhZkFLIFMAXA2gE/CajLI5/PoPHy4q7ev766gUPgNS3nEGFMyxvRKKfvz+Xz11SCOoyF5pmZYG84U2OfLRFHklctlEYahYuaU53nnpNPpSzOZzFAO0w/gfwP4FyLqe+VHX4/XBKEwswer2rYDuBTApwFM0VrjwIED6Ors/HWxUPi+BnYJpfrheb1SyvzKlSvD14oQyMwSVmUnWGvvackpgtXUMrBE02qMaezt7c0FQRDGcQwhRNVxnIbGTOYtDU1Nl/u+P3VI0zsB3AjgB6/ms77qhJJsNdNhBdYPAPgQgFQul8PBgwd7erq6vlWNol9JKXucKOquEPVffPHF4as66GFIOEcbrJGPYN0EPx7r+mq1it7e3mtLpdLDSqmpzOy4QrCbSlXTvt+ebmi4sLGxcbXjOKkht/0GwE1EdN/L+jBjwHk1Oq0h4STTYf0wHwdwJQAcOngIhw4d3NKfy/2rMabDGNMbx3HfpZdeWnmtcJBhqBHKybBzOgdWkxqxEJkZxWIRlUplXxiGTwljFjLQrj2vgcIwU9I6H2v9w2q1+mBDQ8M7GhoazhVCAMBbAaxi5nUAvgurKVUBjLADvRx4WQklYceNsAJdGpYF1zy3jbATOgvASgArwzDE/v37caSzc31QqXxVKvWim073/u53vytks9mXfTJeIhzY56ttQaMSSqFQQKlYRLVcLp+1ZEnXli1bQtd1p0spZxJzmzEmVa1W01EU7Y3j+F8qlcrDmUzmykwms0BYirkyOXbDyjEBMx8BkIf1N+0CcAjAPgB7iSh/oh7uJYOZWwAsBHASrJ1jXnK0JUcjLIFI2NXnYHBCAQDd3d04fOgQevv6vqOU+gaAQyKd7lu1alX1RIzxFQIPOUYgKJdRLBZRDgJUIhupsHz58vyWLVuCTCbTJ4SYTsbMMMZMMnHsB1qbMAwfDoLgD77vvymVSq3OZDJzfN8HrPFx/mj9JCgB6GXm5wBsAfAggCeJ6MDxPNgxEUqiAgL2Jc8B8AZYD+25sINuOoa2UKlUUCgUkOvvR19/f1Aqlb4G4DtxHB8JgqDwWtRkjhdxHKNQLCIol1GtVBBFgyEty5cvjwF07969u+i6bm8cx7M00TSjdVMcx2kVqSgMgntLrvuQI+Vyx3Xf4DnODMfzPMdxpOu6nuM4GcdxEqYDwC7ORlgzw9uT3zqZ+UEAP4F1VB6Z6PgnRCjJFuLBSvPLYFnfRbBxHSMQRRGrSJXDOIy01iWlVNloE2mjI6N1oI0J4zBUlWpVVapVFZTLPdqYxxytN5fiuOvyyy8vvUZlkeNGFEaIogixUqj5m4Zj/vz5VWbu7OnpKQZB0BvH8XSl1BSlVAMrlVZRFALYDGCzlLKRiHxHOI5whOdK2eB4XspxnKme57V7njcjOdqIqCHpYjqsA/VdAPYx8z0AbieiZ442/nEJJZHmM7CUeRqAqwG8J/l7AGEYBlEUHUj21QNa64NKqZzWOlBhWIqNibXW0JGGYsVaa9i/I460VlCqD0LsDbQ+csUVVwRHG/QfK4gIBMI4oQlIBNMiMwe7du3qlVJOYeapsTGTWKmMUkoSs4iJAillYCgmyR6Y2UAIFkIgjmMiImitU0qpdiHEPM91Xycd5wwA02Blp5NgzRAfZeZvAfjKeNvSmISSaCQ1Q9FlAK6BZWMAgDiOK5VK5Yk4jjuUUi8QUZcxJmbmWLAgKSVBa2jHYRkmBAJttNYMBRZSsEilYoe5IIQ4PH369M758+f/MckjxwQiQAgBKQVc14PjjhVQV7ueNIA8M5d37tzZ7bpuSxiGTalUKsXMTuLgllJK1/M81/M813VdN3EZSCISUkrNzAellHu1MQ+YOG4gooWO4ywHsAKJARCWYN7BzDcR0TdHG8+ohMLMDbB2jXZYLvIxWCEUURRVyqXS72Ol7jXG7Hccp+J5Hide2KpkjuA4MTPHieVRh2FojDGmWq2aBOy6rpZSxk1NTeW2traAiNTxvYI/EhBBSgnP82AMg8TETFjJvBSYuQjAOXDggOO6rhBCUHt7e00xqGlcNfEgDesra8CgtikBhFrrDq11h5Typ7B+tItg3Q0nAbidmVcA+DRZX9kARhBKQiRzYQnlfQA+UjuXy+Weqlar3wfwlOM41aampmo6nS4BKMAG5wQA1GgvPZFzBv6E1f//S8kh40FKCdd1YfwUhJQYGaYyPpK5ipNjXCRzXfM5NcIqGc0AWqWUzbBE0w8bSPUQrMxyXnL7XwKYy8zvI6LeWpt1hJJYSefDCj0XIiESYwzy/f3rwjj+puM4R5qamoq+7+dhCUTBbk+LYA1Orcycgo236IM1QT9DRP3HNDN/RCAixcwV2GeWsDG0dahxEyEE0pyCdJwTss0mmuhUAK9PjlNhRQYf1sFYBXAEwN7kOADLdVphuU0XgK8C2A5LJA0ALgBwDzO/nYiqwBBCSWI/ZifHPNjYDwBAoVD4SaTUV9LpdFdLS0sRQAWWSi+G1YDOgeVCY228u5j5MQA/AnAvEVWZeQrspL4PVr0evlIM7Hb3HBHdmIyxxmpF8kCnJf8eypk4uRcAnj+aQy2Z6Jpdx4U1v/tjtEmwkfedGGT5BnZxrYA1HgpYgbEOQgj4vg/f9yGFgHScM5m5O+mzNt7njragkhAMmTz/cthArvNhF6kY51bALuwXYG0qj8Eu5Mbkee+H5TJ/jSS8Q2t9C4D/PrRzYuaZzPxWZn4XM/+aE5RKpd/19vaeUSwWpzLzNGaey8xXM/NDfHx4nJk/ysxvYubzmfn+8S7WWv9hyDhbmHkRMy9h5g8draMoij50lIkDMzcw8ynMfDYzX8jMfUdpNpvcN5+ZVzDzmcx8x9HGkjzLuOfDMPzAOOOUbJ9/JjNfyszrmDmcSL9joI+Zf8LMH2bmK5n5A2zf/bXMXGBmjqKIi8Xiu4FBjlKLImuGFWreDABa65LjOF9saGg4BMuqZsBuRx+AZV8DSAKZEYUhlNYgIjgJu02l08hkMrVLlyfHJgA/gOVOoyKOY5TL5UqNmAFMhrXdpJKxaNjVNQJBEKBUKo0rICdttsByhNbkGNdVYJSqcb4ZsHMVYYKGxiHGsDporREEAeI4HnW8zJzGoNPx3bCBXC81/WQSLDd6M6wD83ewQu8OAD9i5o8qpRDH8fW7d+/e4PCg57MdlnWdA8uKAOAu3/e3JgOcD+AzAN42tLeenh709vSgUCigUqlAKQVmjkgI5ToOeZ7nZjIZp7GxEa2trZjUNvB8FyeDHdX6FEURCoUCgmBA+BawBOJjcPsZVRiuVqvI53KoVKtHE5ZrIQC1JDA5VpsAUCwWoZSqnXeTe16S9bharSIol1Eql2FMPY0mhNwMKzPOAfAJAFe8lP5GwVTY2J/TAfwH7PP8lrU+J47jM6uVyuuFEJfV1KrJsHuVxqD/IJZS/gSDRPJZDErGyOfzOHDgALq7uhHHoclh59NH9CO9Rd7faDho09DNBNICTp/f19o/xV+sTmk9f97swutOmtTWhubmZsDKJiMQRRFyuRzKpRLC6qjZm2P6U8IwRC6XQ7FYRFgZk1kdU5taa+ujGfkyx7xnROPM9jAGDMstK0GAUrmMShAgDEOIIZoQDwZynZQcfw3rPB0VoSphT+9W7Ox6EF3FF1GoHkGsA7gyjebUNLQ3LcDJ7W/EvMnLkHZbRmviAliG8VUAuVjrn4dhuLhSqVIUhZc4sCu0CZZgAgwKRLthVd5ZsOGIA0Ry6OAh7N27F6VSEb3mqW27zToTc+HMpI0hs8jQiKYF3IV91d9gf+dveyf1nfq7N03/+CnT2k6e2djUiEwmA8cZVL60UigmPpGgUkEcRfVNjvNitFLI5/PJSw2gopcetsLMyOdy1pEXBDBsu1dK1Y0bsAQ11DwvhRh4+cYYJKwcURiiHAQoFooIgjLCKAKYkU6254STtGIw9fUzGINIlImwff86PL7nDnSXRssxyyFfOYz9/duxbd/dmNI4H8vnvhtL51wJV45IQVoG4BNa6y9Xq9WdlUqlP6gEbVEYvWWotC9gWXEeVgN5CJa1ng/g8lpLe/fuxd49e1ANS5Vn1fcfy/ELb8TY2k4dGDy5L3ruvA37/vrA0sqHHlk87bI/CcMQ6XQanudBSokojqGUgtIaxpiBFzMRVKshwjBEHMWI4wjxGD6VY0EYhoNHFIGT8VSr1YExK6WcKIoQRxEYiSrsuiDPG1h1xhjLRSoVFAtFFAqWoKtVqyVLKdHY0FBbaM2wRDIVwHthHa8j0Ffej41/uBm7ex+Z8PP0lHbjF09/Gc92bsZFZ/wN2ptOHn7JsjAM31stl78bBMHeaqXSFkeqVFsSNTNhBlaw+TksR5kNK7gCAPbv34/9+/ahHPbn/qD+9dkq956H4wDDzN7W/d2pgeq+/9w5f/mnNd+H53lgZusTGccf8mqgtm3UCLdUKsH3fTCzYOZDcRzv1FrHgsh4vp+C65489DmMMYijCEEQoFgsIJ/P74+iqAC7QDUD1bTvH2JrLDsJVsVeApvkNgIH+5/GPds/j75g33E9z57ex/Gfj38a71z6RcyedObA78qm3V5aDcNtcRx/Q2l9KhP/0IHlHmVY6d0BkIPN2AcsJc8EbLxI5+HDCCql+Gn1jR1V7n3jKP1HAD3P4MdAyBEzgWghgCVgzBl2rfds//o/afBbH1w++/1vGsq2X2tEAlhCMcZAJWOsVCrMiokcShHzvYZ5ExFBOk7ouu48x3W/4jjOgNBhjEGc5D5Xq1WEUfRjWHtGCkC+ubm549RFi7phhdbZsHaS92AU63lf6QDu2X7tqETiIM0paqu4aH6RIAwJRkzlUyqmOx2boG5ic5VDuLvj7/GBc76KyQ3zYIyxHu44dmKt/9wY879c5vvh+y86sOrpflhBthH1PoILAMtme7p7kMvlsEuveyjgrtE4yf3EtBECe9jASmyCmECPsDE/ZsJyInrXMILxtnX+4HXzJq94fnZq8akABrgJvfrhvANgtv8xhgeEWaVUIRJRWbDwPSlZSimklCKVSnEqlfIcx6H6NhhaKahYQSmrBQtmZiF02nUrU6ZMYVhVfRbs3J8Fa+2ug9IhNv7hZvQFe+t+Jwg00/wDPk16gCCPsLWYQ0JwWrbd1+LOnhJy/rze8Lk5mgdtm7nKIWz8wxfx3uX/DAnPbvdag5lPBjAfnvfbXbt2GScxP3fCyibTYCPVNKy6NAOwGk6hkEfAnbu7zGOvHzmVdCcYPzDQISAFEUuG8AH2wZBEAszmd7BlIT4xdAIYaPvVzi++8Bfn/EgLW0HACoETdJq9UmBmMBhGGQIArfV+Zq4SkSdcV3qe1+ZJ2e6l0zSaH4cNQ2kNbazsJZgPauBpz3H8pkmTco2NjWUM1mxxYWXDEQ117N2I3X0P1/0m4ZtGmn1vA814QJMRxEwEThHJtHDgC0GCBOWb5LQfpr3Gtxwu/+HcSJcH2t7TuwVPHtyApbPeOSCDERGEEG9dvnz5PwGJhpO4tCuwskpNFD4dADEzyqUyqtUKduv1+wCeXDcBwI/ImC8T613MzrOuijpYmCfAtIOI9zGZIsgYEtQEcA8R3wrg4NA2CmHn2QcKW5+WUsKREo7rQkj5mtqCauqtSexxp59+et+ePXt2zp49+5n29vbn0ul0V7qx0Ugpxxw0MwOJDCaEOHjhhRfubG5ufv6ss846lHiCp8BqoZNh578O1aiELfvqg/sJgptw0n+2yAX/KSj9lGfSv3JE07q00/rLhlTzY77TsFs6bllKIUjQJM9pfmB68+IHBNXvaI/t+RGqqgRBAiQEBAlIIc994okn5gP1+58Pq5L5sGruPMBuO2EYoqx6ckXevXDY2HdA4EbjxJ1tJix/8qKdEZGVR2/YvFJ6qni4WsUMEM8lMpOZKMVAP0BfB/hGDArR7kN7/j33ullWuJeJd/W1RCgAkj3IIvHmKvszG8/zFCZiUyGCsBZrBQyEQYKZG2G3Hob1m7UOv3VPzzZ0l+uTBzM049EGmn2L1m6f57T2XP1ouULZrLl9y+3urIpuNH5hZqTCMzVXX88CkwjU2uy1PzSz+YyTDuSfGIi57Sntwb7+bZjbfE5CyAQimiSEeBOA3UMJJQUrowhYQpkKWOOXUjF6zJN7GeasYRP3z4tKpzy3Zs2dGgA+NTgXDGxWAPK3bVpY7YubFVwS0NxGJNJM/CwYj2CIwa0z/9wcWLtNk+u6kFJC0NF8XGPjRBIZg2GZwZh0UKvZMqFxSSEgHH/geraOviZYbs6wltgReLF7xJYTC3I/FyDe8alL/ikEgA8m565Zfk0M6+Tr/9kfbupx4kxsVLTMCNMqiFomZWbd1xPsnluNCwOT/GLPQ5jb8icDc8fMMEqdBeCHQ99EKjkYlmDaAGvEYmYUzIvDrVcVJvNwjUjGwqcu3hk2h/5BYnGASVSYmNiwhnVvD0BzNA02xQCu68J13deEnDIOcRwXBlTmeumjlpRfy1QYQSjMPMKgJuDuK5See/hTF//vcS2LVyz+/BFPeo84vrfTlV4oHBKO9PJSuHXBSV3FnaBkS2BmaKOhjJlv+0JdDZKajyedDBgm2VOrps9HHeiRGdPd58YbYA2fXfNwxSHTScx5GDYE4YO4AwnrTpDBEOIRQhwzVxh+uRzdX/iqw4ZE1o3NxeCcCwy+hwEwM4phfUm4jJh2f3bNjglVqEz3VTodKZ+VkvKAABFMW2Z2XYxssdo1QCg60X5UHE8DBs31AoNOLmAIGyUiSOlAUXWYed50X7N861GjrWpwpQ5IiBIEGRJGwoh9qCcUwOaiAAAcxwGN4W1NYDCKp3dAvR65ao8bA34aa0t5SSGbY6j9tdiWcVdGqIp1f7sifWii/a5alVWVSuGwYdNPMEYKlxv99tzQaypx0T6j1tBKIbYWcgEMEgqhPuhlgN8KIeC4DgRk3RYjcGwCRDmvYgABmBUgFGyi2HBj0oArYALyyUhCGSASjGQvx4kBIoH9v5hAKOJxYMz5HwopvGF/j7+ShkNLKhJRHkRa6Vj2B/unDD3vCBecGBWV1lCxgo7jCBg9IopgQ/oYsPKC73lIO5PqQvcYWHLzhsXDKwcdFUSCDEORwCmoJxSNkZWIhnU5MIEESyR1xEtEEIlWIYWEEOJ4MiHrKWyQk8AYBjO/3MWINaxztn5QRGj0694rAu5ZcSwNOzImnUyZEeyUwp66SLxGf4q19SiFOFFiYq0PAKMTioDdAvKAJRTP89CWmj+cyheG5Cye6CAbWhwXQIbBkhgxbDrIAIhEP6zpGgAGVvAwDCUWjWGEIqWEkBKOdOC4DhzhHGtwTy3UcgAmYcXWz2OgbWzsiUbtuQj2mQ4Pv4CIMCkzq+63UOeWfelnp004OzM0KsPGNLJh4cDRANW5j1szM6HiGFEcWy93HEPH8W5gkFBq1ZlrEx/CJjzDcRykUimc3Pan7QCV6wbPgxH6R0M5EK6B8e3WYxawDZAaQIPfthdDasPq5OUMQ+0Hgg0arpP2hRBwHUskruPA8ZyZExjaUNW2ZhoYgNYa2hhobYU7rXXPBNocE1yj83rPtoKdf4aVrA5jlICouW11BTARmWAS/OlXTqhfBjV77a2SvCYQBf3BwXODKFe3KGa3LLFe8sR2ppUChOgABgmlVvtdwU5aAOs9hpQSfiqFkyYvPSntND87rP8rrl+37M1HG2Q2C+F41EKEhqTo7/tgrY8DOGv2ZUNjYaCVqvOtAGCEocZg0HUZw1i0ladceK6LVCqFVCpVvwRHRy28wiRjqltlWil7aAWtDZh5xGo/VjDzcCqIYQlfw8ppe2ADn+tw8tQ3Iu021/3mO5lbs/csGWGcG47NO7INMDRbkPA1x1MO5p5abnhQLndlGrObz0alUkW1Wq2FPxwUQtwPDJrwORloTQ4hAAP5qL7noamhRayY9cHhglyrkOb/ZDecOYr/Z8jDLF/cYjRPhSYHoKsAquMmUrj7z51/9RlDf4uVgjaD00lEXGWubTcClpvUcTghBBzHge/7SKVSyGQyK55++ul6CbAeQysrKFj3fh2iOEaslGXDWpWNMS9HMWEFu93HyZgKzNxhjKnbfidlZuGMGavrbsxXOydPaZ13X3b9sgzGwJYtt7sKTacw00zNZmpX4fn3hqpUpxOePm0V0piGclBGEAQ1d8Ov3va2t/UC9TJKBXbiNQaDbDsBQCbbz9lz37Gs0Z/yeN0oGHOI5Ka165aOGpuSvW9lSrGcAqLXgfjTRHj/8GvOXXD1nozXWidPJLG3dWZzY0yEQZasYXNU6uC6LnzfRzqTQSadXjhz5sz3jjp7Fg6SQoKwRsbzh56MIptYHoU2KMkYs8cY0zuilZeIJGGulgYDrbXQWv9GKRUqZb3N2np0cc6CD6DRr2PG6C3vXTKj5dR9v37m1hGhpcygyiTMcNzUWbEJ3rO397GPHCm8UMc1U24zzpr+bhSLRZSKpVqlhcjYgj0A6gW3CDbvI9JaZwAUjDG/EkJcXctyI2p0373klknfe+zDncxm0HrImMMCv7lh49JNYP4JIDqZ6ffSieeYIHe5FrSAGJcjiW0ZiskN8x4+75Rr6jhMGIaWUIatKK11nIyzJvi9OLw9KSXgeZCOA2aGlHItMz9IRHXXDglcboTddt6JYeU8K5UKqpUKqtVqbfJ2rF69uo6LHQ+YebSQ7DKAvNa61RjjKaVeNMY8QsB5lBjohBCYlJ6Nt57+Kax78gYwD8pwnYVnJ/cF+x788ZbPbmvw25+Y2bLo0IyGhT96bM/2vziSf/KcWFXO2Nn9UIs1itfjzQs+CldNxpHcEZRKA7aae1evXv1A7Y+hhBID6FNKlYwxaWOMp7W+Twhxvuu6sxzHgeM4OGnK4oXvWHLjlrs7rvUBHqrOSjAuA+gygJmI9xgtMwCmjWXRyHiTOv783G+eIoVbtz1EUVSLiagTaGOr01dgX6wE8GwywbWyDjZNxHGGWnXnAljHzJ8B8Oshaawp2MyDU2DDDS8YOgatFEqlEkqlMioVKwqR/WrGy4UAQLfWut0Y06y1do0xdxljlhJRc41QHMfB4pmXIBccwu9euL2ugUgF9GznfcsALHtSppHxWv+uGhdkqMam7XPm/RnmZ1ahu7sbhfxAcaaCYc4OTfkdIBQiYmbOs81ea9Fa+8xcjKLoP4w2f8M+w00y8F8/86Llgtxtd3d8LjBsRhMYCeNXA0JrZtajHz73O/Ma/SlThp9TcQwz4NYfRFtfXxg1NpY8z4sBpLTWB40xW5AEfgsi6yIfaYdaBFtO/HFm7oDlnFNgiWQxLGepQ6FQQCFfQKlUrEXedTtK3TPeM70UEFHMzD1CiB4dxxljjGfi+JBi/jaAzyilhJQysecYvPnkv4QrU9j8/NehzEjTTqwryFcqY9qmBTk4d96f49UNTDsAABUCSURBVHWtV9qUm96+wcBwopsuXL1629Drhxukqq7rHgnDcAqA9jiOU8y8xSj1M230FcwMz7OLf9GMt509temufXds/R8P9ZR2v2GUtsaYENGzZPYVT1/8+r/9Eym8ET4NnVgFrSCHehvlwoWxqVbzWusqLKHIOI43gHEuAI+ErRjgSAnpjDqcFckxLorFInr7+tCf60cYWg2cgVtXXXLJCRNk9ejpQEWt9WEmatFatxkgo5R6mI2ZAeB9lHAUKSW00lg+532Y2nQK7n/hdhzI/WG09kbFjJbTsXzW1Zgsz0DXkS709fdbVdjie5VK5evD76mbTSIyzNyrlDpsjGkgYxo1s6u1/pEwpkUrtVKn00inrSw0pXHeSR8/7ycnPXfkvu2bn/9mqav4wsnMpn14uwACT6b3L5z2psOrTv3EyZMbThpV8DXGoFwuo1Kp2Gj6OEKsBhUtIjL9/f15x3H6jTFNcRz7SqndWuuNBLwDwIDBzfXMAFEfC/L5PHp6etDT3YNyacD1tF1KOWrdkIkiiqMhqvboyYi1aMNyudxIRL7WupGZfcN8t1KKALw3jmM4UiKOY7iRg2npxXj3kluxq+9BPNP5K+zvfwLVuDiibd9pxMyWM7Bw8nmYlV6OalnjSL4T+Xx+qBx4l9L62o6OjhF71YhlR0TVXC53gIgaDNFJRmtfa81xHH8rIlKxUm9TSiGTyQx4QE+btmrJadNWATZr/kFYbSSXtN8Ouw2djsEivTV0a62LYbW6oJamYaPUk1pn1epAfGkNra2t5TAMjwBo01q3JILfT40xC8G8GDZ6DG7kIPI8eMlxNFQqFeTzeeT6+9Hb14egPDBXXWTMX79t9erj0naYGUE5QLlcRjkIUK1WEccRMNKYCAAgoiCXy+0XQvhENBdASinlG2Pu1loHAP4sIvJqOUOOdCAdiTmZN2LeKW8ZiOOpiWh6SD5RGIaoBAF6u4oolQZTRWCzAL5rjLklnU73jFaBc1T+3NLSki+Xy7u11i7bpGhPWR3tO3EYdler1XdVKhUvk8mgoaFhqOA4DaNk8g9Hkty1T4XhbYp5pdZ6gUm2nGqlglKpRiTxCOceEcV9fX1dqVSqjZnTxhiPlYqUMbcppT4F5sUAMJRNu64Lx7HW2lqIpQ121gjDKirVKspJxcZioYjEDwYAXSD6RH+x+BAz07HWc4mS9IxyuYx8Po9SsTRAKON5xltaWvLFYnFX8s2gOUSU0nbB/sIYs9sYczWBTrWefRu6aH1bidecKAkGN9aqnNiBwjBEtVJBpT6D8gUG/o2I7kqlUodXrlw5amzLqIRS24IqlcpOYaOHpjOzr5mNDsO7GeK5oFR6b8HzTvd9H+l0Gpl02pZ0GEU2qFQqdkWVSqhUKnEcx7/WSv2UgG5I+U4e8kC10MsB2FordZg0aVIpCIL9ABqYeZpidpVSpTiObzVKvVsZc1HyYkGwYX1CiAHvck2b0togjiO70oZVagSwnZhvhhC/3LFjhzoWIjHGoBJUUCwVEZQDlEpFyyWDIYZkNXa0QjL/fcVicWccx0YIMVsIkTHGpJj52TiOb9ZarwLwVmaeNVptOOvI1FBKQ2uFMIqGyiEAcADMv2ainxHR05VKpfOCCy4oj/WcYwqgyX7Z1d3dzS4QKyFmaq3TxlBaqeqO2JibTBCcTUQrpRAnS8dpdh0H0nEGtA6t7AAT30FPFMVPgM1vIMTzksiRrpsmosNszItxrBANrmSQFWNjBkZ8JYuINDP35HK5F7XWRETtbGWpahyG/x5p/YRW6iKtzRkMduuci0O8wTVP6TDsJuDnmvkuY8xTad8vH0sx5CiKuFwui1KpJPL5PMqlEspBMKIfI8S4+2GihfYlJc2qWuvZWutWMpQGEDHzOqXU75VSi43Wb2CDkwybqQx2ONGMhqEKoJuse2ALE21jYJeUslPbIovj2ofG1VSSF9JVKBSiqFAoQ6lZRqJFa0pprY1S6mETx48qracZ4FRmnkXMUwxzKrGqVszgp19fIKI9RBSYOJ5EnpcWti7LOqPU+mHmeoIxmon2aq23jzG2iJkP9fX1GWNMDGCaYE4ZIqm17lBR9GSszHylojMN8ymw4YVD65nVHKEhiHrBvIeAbUz0pGHeBeDQ1KlTc8uXL59woFIURSiXy265XO4pFUpfKpVKCMplaGPsdmAMQYgSGbPX1fqo3+RJVnf//v37q77vF0mpWcYVU4QRGQAZZg4APKC0fsAY08patzHRLNggbQcACIjB3AvgEBP1g6iHmUsE9DHzkb6+vt41a9YcNXTiqCptwgb7C4VCxctkckaI6Yp5ssvcCK29UAhhtO4xcXxEwyY11e5lIRjMsWSuGiGKQohuIio7jqM8z/OEEA4zG8HWxjgkwZvY2hXyc+fOHbOEVWJ7OHzkyJFQCFGUnjdNKNUkhEhBSiA2e8C8i+21TcycEZZQHANowRyzEBUypp+FqGpjilKIHtd1D3d3d/dfeOGFE09eTsz9YRjKarVajcLKNq21DSVNVrchIgJ6yHG2r7rwwgkLx3PmzKkw8/49e/bkXMs924moBdb94DmOI+I4DgxREcAeUcdCoQ2RFswxGVPWRAUi6mbmvtWrVwcT3VInaPsgBlBh5oPd3d19cRy3AGgxxjS7RGkhhBuzkEJHpIggjDGaSLMxVSllCUR5AeRbW1sLxWKR29raYorjHLuugwiI3Aiolw/gMsfGcfqefvrpcV9WskV2HzlypBwEQZ/rulOYOaO1TsPVriFXstZkjAkAG+Riah8ZkNKAWRmiCpgLAHqr1Wr+sssuO9ZatyZQKhdF0X6lVApKsUm0LyklVC2pCoAQor+1tfWYwymT3KscM5d27tx5hIgambmJ7Gfq0lJKL4oiYYwhKAUjBDNzDCAUQgTGmJI2ptTa0lI699xzQzrGDy28YmHud9xxldzR3u0il0u5aZk2UZzSklwpbNl0HTnaYRkKT1WjUliZsSAdfmzZVmVTP+z9nY0djiq1j6kuOI3dpu+xnXE2O37VpBqyP18yjzQ9CICIcZF0RVdszCQokQIpR0BIQ0yCiQ2MBjsKjqk6jLIyotxc8cqzMTs6WiZC9r6VDpXz1wE4n4n+MXvJtg0AcNumhX7ZpDMsBjWASAuGWzUi9t/PxB8C6B5uaL5lUXd7XOtn7YalH2HgQ5R4jJhAZKBBMAxoEB8iQ49JV/zs8xduHQiLyGYhsGiR09DiuGXWPnTGA4WOE/mkvJARS+O5iIUrIq/fiwrtfnz9ys2aCPyKEcpQMIPuvPMqsaO9e6D/Rd3tvGPHnTzRl3wisHbdkhUsaDOAUCrnrGuvfHx/9o5FHtJpB5WKQGOTSLmKqrHDKBVNM1p0AXmNHTvU9deDa0R8NGR/cUabiL07mXA+gf/7dZdu/9q412chsGLJzcT0NwC+fv2lHR8fOHfHIg8N/r8Q479NoOs9IP7Y9ZdsH/WjlsygG24YZBbjPdOr8r0eO5jxV+ErASOJiUkDrJUXMgAk6Q9HFe6y2Yn34wUNpDyr0jEfPTh70aKraAdsRiCD6+wabY0R9ZmUARgE3G0Y/ymIrAmBjANQMzNOgi00vAhMd2bXLTs/e/nWbcP7SYhigDDGe6ZX9cNO/w9jg3hsIZPYcPKWn85e1nHX0HPZLASWLUsJqe9hg1sAOkeQviGbxRUvhVu/zB92At2wacU0Vxr6+9VbO8dj1Tf97PXTYriyzS32furincdUUyt738oUgnKrYyKpFJWz79ieO/pdABTlINmAYdJxOOEPIGU3ndPs6GoTuY6J/ab+7KrNr04Nfxr5/iwxbA1u27RwSw7N32XwG5hoBVasmAo8ftxOzRNCKNl7lrSSS9cCaJw+XXwy7MqLnGn+q7Wb+P1k1FxlgLUbl+xeuwHfN1u2f6NG2dksBC1f+mEAH1HAAgKcftN0YO2GJXdOmy6/fLQEs+zGJUvA4qMU5N8Mg+mahCSX8zdsOPsZEP/HdRd3/Gg04ly7ccmHmMXFBG5ioAGEVOg0fD+7YWmVgDyz+Gz2sq11mk82C4HlZ18pCFezic7QJNoQG00qf+SGDUsfIhL/dt0lWx89EfN5IvCpi3eG2Y1LnyWGAtAgdTiiYNux4PizwIc24tBc2NqnV3d1xwv6TdN6Bt8KxjIQGIQUQOcw6F+xfOm3ALsqsWzpDwB8G/aztbUkqCUMuulwp/7hbZsWjghDqGHt+rP/hpgeIPDHwTgzScGLAJoN8CVg/J8bNy796T/8dEVdpafbNi30jaELAL6KbSlUB4DPzJeTrXB0YSpVX5Muu3HFdLF86Z0E/gkzXwkbCRcmfS4GcA2z2XzDhrM/fyLm80SBrEPWASPQ0n9JaSYnhFAMCwkbHJwzWn4XwNsY9BVivJlZrDRGXAKmLwMoEfCRG9YvfSuZMEuE9zP4m8aItzCZP2XCaiL8HYBOAq3JmcZPDu8rm4XIrl96MxN/CUADiP8N4EuZxZ+SMRcw0RUAbgFwgIHLtaPWZe89c+DzsKrULgSbLzKbc8C0BjaIqR/gy0mYZVI5b/ncBVsHtqHsxhXTidVP2IZKHgJwHTHOYzKrmPjtsM9wBwAJ8D/csPHsfx5vrsgaxcbFmjV3aholCWzkxNOYQnf2niWtZPBx2GJMj15/8eMT/trXaDghW49wFLMRtSj2mQS65vpLtw3EbzCDbvjlmc8jkj4RPgXCtwGaRYS/u/6S7f84tK3sPUt2Co/AjJsZ9GfZ+1Z+dagMQMuWvA+EzwGIQfTJ6y/pqIsHzGbxjP+mZQ9XQ3M/AV8BsASRvPWOO6764Jo1d+rPrnm4AuBpwG5dZC0EynHktqE2ByCx/Zidt4DwRjA9x0zvH649ZO9b+ZRT6r/fEJ5g0LVg/nR2/dIHhguZA3NBuPKGjWdPHs3ZCQA0kPiDt4w358nF09duXLbYQEthiI0hFoIbAJzChI8x8CYAARF/aaKq/Fg44cIsAXddN4RIgJoa9mRXdsPS38CWSJ9HjN9+4ZKOL1037P7sO7bn1q5b8hsI6gRwihPkp8F+FQI3/WLZDKXMtUmb1113ybbbMfx+K8zl/+mOc39VTIdfAPHXifD+HekXvgX7mbWhI3MSnzzFJh4RNvhM4863k8H7ASqORiQAkF21WQE4eNPPXv8dJd3pAD5JhC9m71j0i+yaHaXh14PxHoDfM9b8HaNl6xpm8xECxUxgkuxyfQLbATD9z+su7njwmFodBSeOUAgEBoytbz8qhDD72YgCgEYQ/mMsKidCjgldYExXdp/dCwBamfNgA6BeMEbcNt5wPrvm4coX737DryM/Xk+MDxLRn2EEoYyN7H0rHZTz7wIABn8ve/m2EUQyFHHHU93iDWf9Gxu6BKBTKOOdB2DjKJf+ihnPQGBs7zFDkS32dzSu0gtr80kyLHkXQAUCekD4DRn+yRcu63hhvAYmihNHKJxk22naMdYlgkWfTvZeI2jP2G0ZBeNoWFPCQGKTAS1LaOvXw7WS0RBNyuRFkPs9gz4I4KzbNi30J6p6e5X8pJhwJhgg4nVHuz6bhbl5g9kTsfMgCAsY9AaMTig/zl7W8Z2xBPW+kscAwGn/C0TjEwozvgXi9UT0dTDOBMTWjKs+XPba9IlW2U/k1iMB5D3tjP29mdgxcBQAGLAZ9zs6IOtJI2M1kNu3LHM7O017cvLg2DcOIrtqs7ph09KDCd+a0qdbmjAsX3kshEqkBXEziJkgJ2R/CANdRoM8nMg9oyfI2xr3GI9gmUFrNy49ajVwIipdd0nHw2s3nJ0F8e0AXxUo8Xh29eYvT2S8x4ITovUMgYky5Yl80SKCkROieJaWrRzeVSHUiuuBJxw1zUw12aMK6RxbyQpKknN5Yv3NWJBmsN1SCHzcdVTuvPOqCb0XAksiMCtzH6ymp8B0Q3b9WW8/2r3HihNNKBOGdOJjksJnLEgzEllFEJ15lMsHIAyfmvxzb/biRwsTvY+kiGDVYWKYcXOrayjv8hwBOh0AiDDmFnyicf2V2/OS8CNm/DuANJH4fnbDilG/SX28eNUI5VjxsWVbFcDbAFSZ+W3Z9ctG1GEdjuz6ZRkWAx98GNWDOiaMKTAjsbTSX0zkllK6uoTth5KqJMzvj6m/lwAi8KnlhQcgcSvsJ9+mEfS3xzNYHiv+aAiFCMzCPMHgdQAaifhrR5sIIvM/wDgPwD5X4PvH1OHWrVVB9EsQ9gN4yw3rl3x6vMuzm85pZqKbADQy6LtfuOjJCRVCPFFYs+ZOjVK4E6AvAtgN8Kp+03TriWr/j4ZQAGDGVPeIMPQ1ADuTifjxjeuXnDL8uuymc5rXblx6LYC1AAyYPvP3F3d0H0tf2SyMEe4TMHwLAAOif167cem1o5Uju3H9klOEiX4A8CoAz7o6Wnucj/iSkF2zI+Kg+iCYboTN0f6rtRuXHPW7ihPBiVSPJQiOFzQczWQkAThauRO5DqQHTVDXLN8aZ+9YtJUa/M+BcSOAKwyJ829Yv/SXRLyNQUUGnU4mejvbmv6KwJ++7rKOcXKG2QGgncgfMZ7sxY8WshtX/BhGTSHC/2LGjRHkh7Mbl/5S2PoxGcO81IAuhM1f3kGM93/+iqdGmsvtBwHG/rDgiGGRADEINDJ/mGwlRDNK3cvsmh2lL25auiE2OA3A3zLT19auX/rcdZd1PDyinWPAieEoRgrQQH2VMRELkzjuUBhPmNXCrxUcLLOoD/TJrtlR4nJ4L4GusT4WJhDexaCbANxG4I/DEsnvAbp0vGgyYYmwAqBQC1wagccf73JN/FUw/RWAhwBaQIz/xox/YcbNBFoD+8K+Y4y46LrLOp4Y3kTkarIlyVAhmKOq5zvauwnE9pvFTHXaYV96NiVFMypiWFBTDX93UUePYP4O2Q9gSxb4RnbjilGrYU8UJ4SjGJIHCfFHibgnevKxMe0ovpLl2DUfJ+aCDtTusUcV5Dh2/5ZZKmpq2jL8dHbNjlL2jkWPOml3l5LidtJmMQjtDEFEJgdgO5P/6NG0HMN0EGzeJyS6X1c67TDw5IhrrEvgqSNfvvfMO6qReMBAvJ7IvI6JGtlAE+EQCfPIFy56YvtYlua0KYUBNd5MDt/iu2LreGMCgBlNRTpcFHeA1G9d16nLPm+rHOA+avoewOscojFSWcDZO6K9aHA/Tyy/TYwgpQsvqa7L/wXbl0TCz0zTowAAAABJRU5ErkJggg=="/>
                </defs>
            </svg>
        </div>
        <div class="title-wrapper">
            <div class="page-title">
                <span>Interviewing for job "<?php echo trim($portal_job_list['desired_job_title']); ?>"</span>
            </div>
        </div>

        <div class="right-section">
            <span>Demo</span>
        </div>
    </div>

    <div class="main">
        <div class="conversation">
            <div class="bot">
                <div class="icon-wrapper">
                    <div class="image">
                        <img src="/assets/images/Michael.jpg" alt="Michael Photo" />
                    </div>

                    <div class="layer-1"></div>
                    <div class="layer-2"></div>
                    <div class="layer-3"></div>
                </div>
                <span class="caller-title">Michael (AI Interviewer)</span>
            </div>

            <div class="applicant">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="245" height="245" viewBox="0 0 245 245" fill="none">
                    <g clip-path="url(#clip0_1957_6058)">
                    <path d="M122.585 155.507C91.2411 155.507 65.8316 130.098 65.8316 98.7536C65.8316 67.4095 91.2411 42 122.585 42C153.929 42 179.339 67.4095 179.339 98.7536C179.339 130.098 153.929 155.507 122.585 155.507Z" fill="url(#paint0_radial_1957_6058)"/>
                    <path d="M22.3329 236.689C40.5422 255.697 90.0841 242.733 118.484 242.733C146.885 242.733 202.057 255.697 220.267 236.689C204.043 167.25 151.985 160.297 118.484 160.297C84.9836 160.297 41.8023 175.686 22.3329 236.689Z" fill="url(#paint1_radial_1957_6058)"/>
                    </g>
                    <defs>
                    <radialGradient id="paint0_radial_1957_6058" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(135.723 78.7614) rotate(180) scale(83.1383 83.1383)">
                    <stop stop-color="white"/>
                    <stop offset="1" stop-color="#D1D1D1"/>
                    </radialGradient>
                    <radialGradient id="paint1_radial_1957_6058" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(135.21 186.996) rotate(180) scale(84.09 84.09)">
                    <stop stop-color="white"/>
                    <stop offset="1" stop-color="#D1D1D1"/>
                    </radialGradient>
                    <clipPath id="clip0_1957_6058">
                    <rect width="244" height="244" fill="white" transform="matrix(-1 0 0 1 244.867 0.288208)"/>
                    </clipPath>
                    </defs>
                    </svg>

                    <div class="layer-1"></div>
                    <div class="layer-2"></div>
                    <div class="layer-3"></div>
                </div>
                <span class="caller-title"><?php echo $portal_job_list['first_name'] . ' ' . $portal_job_list['last_name']; ?></span>
            </div>
        </div>

        <div class="button-wrapper">
            <div class="timer-wrapper">
                <span class="timer">Total Call Time: &nbsp; <span id="time">00:00</span> </span>
            </div>

            <button class="end-call">
                <svg width="38" height="15" viewBox="0 0 38 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.30351 13.643L1.32627 10.6657C1.04876 10.3905 0.831798 10.0603 0.689284 9.69631C0.546767 9.33234 0.481838 8.94262 0.498655 8.55211C0.51547 8.16159 0.613658 7.77889 0.786935 7.42853C0.960213 7.07816 1.20476 6.76784 1.5049 6.51745C4.89055 3.79545 8.86748 1.90556 13.1161 0.999626C17.0377 0.125472 21.1036 0.125474 25.0251 0.999627C29.2913 1.91143 33.2827 3.81535 36.676 6.55714C36.9753 6.80678 37.2193 7.11599 37.3924 7.46508C37.5656 7.81417 37.6642 8.1955 37.682 8.58479C37.6997 8.97408 37.6362 9.36279 37.4955 9.72619C37.3547 10.0896 37.1399 10.4197 36.8646 10.6955L33.8874 13.6727C33.4105 14.1591 32.773 14.4555 32.0938 14.5066C31.4145 14.5577 30.7399 14.3601 30.1956 13.9506C29.1171 13.1235 27.9486 12.421 26.7122 11.8566C26.2235 11.6349 25.8085 11.2778 25.5166 10.8275C25.2246 10.3772 25.0678 9.8527 25.0648 9.31605V6.79532C21.1675 5.72353 17.0531 5.72353 13.1558 6.79532L13.1558 9.31605C13.1528 9.8527 12.9961 10.3772 12.7041 10.8275C12.4121 11.2778 11.9972 11.6349 11.5084 11.8566C10.2721 12.421 9.1035 13.1235 8.02506 13.9506C7.47497 14.3647 6.79188 14.5621 6.10571 14.5054C5.41953 14.4487 4.77814 14.1418 4.30351 13.643Z" fill="white"/>
                </svg>
            </button>
        </div>

        <div class="footer">
            <p>Powered by</p> <img src="/assets/images/automotoHr-logo.png" alt="automoto Logo" />
        </div>
    </div>

    <!-- Microphone Connection Popup -->
    <div id="microphonePopup" class="microphone-popup">
        <div class="microphone-popup-content">
            <h3>🎤 Microphone Access Required</h3>
            <p>We need access to your microphone to conduct the interview. Please click the button below to enable microphone access.</p>
            <button id="enableMicrophoneBtn">Click Here to Enable Microphone</button>
        </div>
    </div>
</div>
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script>
    // let chatId = '';

    const job_list_sid = `<?php echo $portal_job_list["jobs_list_sid"]; ?>`;
    const ServerPath = '<?php echo $creds->API_BROWSER_URL; ?>';
    let socket;
    let currentAudio = null;
    let interviewStarted = false;
    let mediaRecorder = null;
    let audioStream = null;
    let audioQueue = [];
    let isPlaying = false;
    let audioContext;
    let nextScheduledTime = 0;
    const sampleRate = 24000;
    let currentSource = null;
    let gainNode = null;
    // Crossfade duration in seconds
    const CROSSFADE_TIME = 0.008; // 8ms crossfade
    
    // Speeking detection
    let voiceDetectionAnalyser = null;
    let voiceDetectionSource = null;
    let voiceDetectionDataArray = null;
    let voiceCheckInterval = null;
    let isSpeaking = false;
    let silenceTimer = null;
    const VOLUME_THRESHOLD = 120;
    const SILENCE_DELAY = 2000;

    // timer variables
    let timerInterval;
    let frequencyAnalyser = null;
    let frequencyDataArray = null;
    let frequencyCheckInterval = null;

    document.addEventListener('DOMContentLoaded', function() {

        function startCallTimer() {
            const timeElement = document.querySelector('.timer #time');
            let [minutes, seconds] = timeElement.textContent.split(':').map(Number);

            timerInterval = setInterval(() => {
            seconds++;
            if (seconds === 60) {
                seconds = 0;
                minutes++;
            }

            // Format MM:SS
            const formattedTime = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');

            timeElement.textContent = formattedTime;
            }, 1000);
        }

        function stopCallTimer() {
            clearInterval(timerInterval);
        }
        startCallTimer();


        // Initialize AudioContext
        function initAudioContext() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)({
                    sampleRate: sampleRate
                });
                nextScheduledTime = audioContext.currentTime;

                // Create main gain node
                gainNode = audioContext.createGain();
                gainNode.gain.value = 1.0;
                gainNode.connect(audioContext.destination);
            }
            return audioContext;
        }
        
        function startInterview() {
            interviewStarted = true;
            try {
                socket.emit('startSpeechRecognition', {job_list_sid});
            } catch (e) {
                console.error('Error starting interview:', e);
            }
        }

        // MediaRecorder Setup and Frequency Tracking
        function setupMediaRecorder(stream) {
            try {
                // Check supported MIME types
                const mimeTypes = [
                    'audio/webm',
                    'audio/webm;codecs=opus',
                    'audio/ogg;codecs=opus',
                    'audio/wav',
                    ''
                ];
                
                let selectedMimeType = '';
                for (let type of mimeTypes) {
                    if (MediaRecorder.isTypeSupported(type)) {
                        selectedMimeType = type;
                        console.log('Using MIME type:', selectedMimeType);
                        break;
                    }
                }
                
                // Create the MediaRecorder
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: selectedMimeType,
                    audioBitsPerSecond: 16000
                });
                
                // Configure handlers
                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0 && socket && socket.connected) {
                        socket.emit('audioData', {
                            clientId: socket.id, 
                            job_list_sid, 
                            data: event.data
                        });
                    }
                };
                
                mediaRecorder.onerror = (event) => {
                    console.error('MediaRecorder error:', event.error);
                };
                
                // Start recording
                mediaRecorder.start(100);
                console.log('MediaRecorder started!', mediaRecorder.state);
                
                window.mediaRecorder = mediaRecorder;
            } catch (err) {
                console.error('Failed to create MediaRecorder:', err);
            }
        }

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
                    
                    // Setup MediaRecorder for sending audio data
                    if(audioStream) {
                        setupMediaRecorder(audioStream);
                    } else {
                        console.error('Audio steaming not define!');
                    }
                }
                else {
                    console.log(`Status: ${data.status} - ${data.message}`);
                }
            });

            /* -----------------------
            3. Method (Play in audio wav format)
            ----------------------- */

            socket.on('message', async (data) => {
                try {
                    
                    if(isSpeaking) {
                        return;
                    }
                    const parsedData = JSON.parse(data);
                    
                    if (parsedData.type === 'audio') {
                        initAudioContext();

                        try {

                        const binaryString = atob(parsedData.data);
                        const len = binaryString.length;
                        const bytes = new Uint8Array(len);
                        
                        for (let i = 0; i < len; i++) {
                            bytes[i] = binaryString.charCodeAt(i);
                        }
                        
                        // In Audio play format
                        const blob = new Blob([bytes], { type: 'audio/wav' });
                        const audioUrl = URL.createObjectURL(blob);
                        
                        // Add to queue
                        audioQueue.push(audioUrl);
                        
                        // Start playing if not already playing
                        if (!isPlaying) {
                            playNextInQueue();
                        }

                        } catch (decodeError) {
                            console.error('Error decoding audio:', decodeError);
                        }
                    }
                } catch (error) {
                    console.error('Error processing message:', error);
                }
            });

            // Handle errors
            socket.on('error', (error) => {
                console.error('Socket.IO error:', error);
            });

            // Handle disconnection
            socket.on('disconnect', () => {
                console.log('Disconnected from Socket.IO server');
                
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

            return socket;
        }

        /* -----------------------
           Method to listen audio
        ------------------------- */

        function playNextInQueue() {
            if (audioQueue.length === 0) {
                isPlaying = false;
                return;
            }
            
            isPlaying = true;
            const audioUrl = audioQueue.shift();
            
            // Create and play audio
            const audio = new Audio();
            currentAudio = audio;
            
            currentAudio.onended = () => {
                URL.revokeObjectURL(audioUrl); // Clean up
                stopFrequencyAnalysis();
                playNextInQueue();
            };
            
            currentAudio.onerror = (e) => {
                console.error('Audio playback error:', e);
                URL.revokeObjectURL(audioUrl);
                stopFrequencyAnalysis();
                playNextInQueue(); // Skip to next
            };

            currentAudio.onplay = () => {
                analyzeAudioFrequency(currentAudio); // Start frequency analysis
            };
            
            currentAudio.src = audioUrl;
            currentAudio.play().catch(e => console.error('Failed to play audio:', e));
        }

        function analyzeAudioFrequency(audioElement) {
            if (!audioContext) return;
            
            // Create analyser if not exists
            if (!frequencyAnalyser) {
                frequencyAnalyser = audioContext.createAnalyser();
                frequencyAnalyser.fftSize = 2048;
                frequencyDataArray = new Uint8Array(frequencyAnalyser.frequencyBinCount);
            }
            
            // Connect audio to analyser
            const source = audioContext.createMediaElementSource(audioElement);
            source.connect(frequencyAnalyser);
            frequencyAnalyser.connect(audioContext.destination);
            
            const layers = document.querySelectorAll('.bot .layer-1, .bot .layer-2, .bot .layer-3');

            // Start frequency monitoring
            frequencyCheckInterval = setInterval(() => {
                frequencyAnalyser.getByteFrequencyData(frequencyDataArray);
                
                // Calculate average amplitude across frequency spectrum
                let sum = 0;
                let count = 0;
                
                // Focus on speech frequency range (300Hz - 3400Hz)
                const minFreqIndex = Math.floor((300 * frequencyAnalyser.fftSize) / audioContext.sampleRate);
                const maxFreqIndex = Math.floor((3400 * frequencyAnalyser.fftSize) / audioContext.sampleRate);
                
                for (let i = minFreqIndex; i < maxFreqIndex && i < frequencyDataArray.length; i++) {
                    sum += frequencyDataArray[i];
                    count++;
                }
                const avgAmplitude = count > 0 ? sum / count : 0;

                // Normalize amplitude (0-255) to scale factor
                const normalizedAmplitude = avgAmplitude / 255;
                const baseScale = 0.5;
                const maxScale = 1.2;
                const scaleRange = maxScale - baseScale;
                const intensity = Math.min(normalizedAmplitude * 3, 1);
                
                // Animate layers with different intensities for depth effect
                layers.forEach((layer, index) => {

                    const layerDelay = index * 0.05; // Stagger animation
                    const layerIntensity = 0.9 + (index * 0.05); // Vary intensity per layer
                    const targetScale = baseScale + (intensity * (maxScale - baseScale) * layerIntensity);

                    // Smooth interpolation instead of direct assignment
                    const currentScale = parseFloat(layer.style.transform.match(/scale\(([^)]+)\)/)?.[1] || '1');
                    const smoothScale = currentScale + (targetScale - currentScale) * 0.3; // Lerp factor
                    const rotation = (normalizedAmplitude * 2 - 1) * 1; // Subtle rotation
                    
                    setTimeout(() => {
                        layer.style.transform = `translateY(-50%) scale(${smoothScale.toFixed(3)}) rotate(${rotation.toFixed(1)}deg)`;
                        layer.style.transition = 'transform 0.1s ease-out';
                    }, layerDelay * 1000);
                });
                
            }, 100);
        }

        function stopFrequencyAnalysis() {
            if (frequencyCheckInterval) {
                clearInterval(frequencyCheckInterval);
                frequencyCheckInterval = null;
            }

            const layers = document.querySelectorAll('.bot .layer-1, .bot .layer-2, .bot .layer-3');
            layers.forEach((layer, index) => {
                setTimeout(() => {
                    layer.style.transform = 'translateY(-50%) scale(0.5)';
                    layer.style.transition = 'transform 0.15s ease-out';
                }, index * 100);
            });
        }

        function hideMicrophonePopup() {
            const popup = document.getElementById('microphonePopup');
            if(!popup.classList.contains('hidden')) {
                popup.classList.add('hidden');
            }
        }

        function showMicrophonePopup() {
            const popup = document.getElementById('microphonePopup');
            if(popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
            }
            
            // Add click event to the button
            document.getElementById('enableMicrophoneBtn').addEventListener('click', setupAudioRecording);
        }

        /* ----------------------------------------------------
          Microphone Speaking Detection and Frequency Tracking
        ---------------------------------------------------- */

        function detectSpeaking(stream) {
            const source = audioContext.createMediaStreamSource(stream);
            const analyser = audioContext.createAnalyser();
            analyser.fftSize = 1024;
            
            const dataArray = new Uint8Array(analyser.fftSize);
            const frequencyDataArray = new Uint8Array(analyser.frequencyBinCount);
            source.connect(analyser);

            // Cache applicant layer elements for frequency animation
            const applicantLayers = document.querySelectorAll('.applicant .layer-1, .applicant .layer-2, .applicant .layer-3');
            let lastActiveTime = 0;
        
            function analyze() {
                analyser.getByteTimeDomainData(dataArray);
            
                // Compute average volume from waveform
                let sum = 0;
                for (let i = 0; i < dataArray.length; i++) {
                let sample = dataArray[i] - 128; // Normalize to -128 to +127
                sum += Math.abs(sample);
                }
                const avg = sum / dataArray.length;
            
                // Simple threshold: tune this value
                const threshold = 8;

                // Frequency analysis (frequency domain)
                analyser.getByteFrequencyData(frequencyDataArray);
                
                // Calculate weighted average for voice frequencies
                let weightedSum = 0;
                let totalWeight = 0;
                
                const fundamentalStart = Math.floor((85 * analyser.fftSize) / audioContext.sampleRate);
                const fundamentalEnd = Math.floor((300 * analyser.fftSize) / audioContext.sampleRate);
                const harmonicsStart = Math.floor((300 * analyser.fftSize) / audioContext.sampleRate);
                const harmonicsEnd = Math.floor((8000 * analyser.fftSize) / audioContext.sampleRate);
                
                // Weight fundamental frequencies more heavily
                for (let i = fundamentalStart; i < fundamentalEnd; i++) {
                    weightedSum += frequencyDataArray[i] * 2;
                    totalWeight += 2;
                }
                
                for (let i = harmonicsStart; i < harmonicsEnd; i++) {
                    weightedSum += frequencyDataArray[i];
                    totalWeight += 1;
                }
                
                const avgAmplitude = totalWeight > 0 ? weightedSum / totalWeight : 0;
                const normalizedAmplitude = avgAmplitude / 255;
            
                if (avg > threshold) {
                    onVoiceDetected(avg);
                } else {
                    onSilenceDetected(avg);
                }

                // console.log('normalizedAmplitude', normalizedAmplitude);

                // Frequency-based layer animation with smooth transitions
                const frequencyThreshold = 0.10;
                const isCurrentlyActive = normalizedAmplitude > frequencyThreshold;
                const baseScale = 0.5;
                
                if (isCurrentlyActive) {
                    lastActiveTime = Date.now();
                    
                    const maxScale = 1.2; // Reduced for subtlety
                    const intensity = Math.min(normalizedAmplitude * 3, 1); // Amplify but cap at 1
                    
                    applicantLayers.forEach((layer, index) => {
                        const layerDelay = index * 0.05; // Stagger animation
                        const layerIntensity = 0.9 + (index * 0.05); // Vary intensity per layer
                        const targetScale = baseScale + (intensity * (maxScale - baseScale) * layerIntensity);
                        
                        // Smooth interpolation instead of direct assignment
                        const currentScale = parseFloat(layer.style.transform.match(/scale\(([^)]+)\)/)?.[1] || '1');
                        const smoothScale = currentScale + (targetScale - currentScale) * 0.3; // Lerp factor
                        
                        setTimeout(() => {
                            layer.style.transform = `translateY(-50%) scale(${smoothScale.toFixed(3)})`;
                            layer.style.transition = 'transform 0.1s ease-out';
                        }, layerDelay * 1000);
                    });
                    
                } else {
                    // Gradual fade out
                    const timeSinceActive = Date.now() - lastActiveTime;
                    if (timeSinceActive > 100) {
                        applicantLayers.forEach((layer, index) => {
                            const currentScale = parseFloat(layer.style.transform.match(/scale\(([^)]+)\)/)?.[1] || baseScale);
                            const fadeScale = currentScale + (baseScale - currentScale) * 0.15; // Gradual return to 1
                            layer.style.transform = `translateY(-50%) scale(${fadeScale.toFixed(3)})`;
                            layer.style.transition = 'transform 0.15s ease-out';
                        });
                    }
                }
            
                requestAnimationFrame(analyze);
            }
            
            analyze();
        }

        // Updated voice detection handler
        function onVoiceDetected(volume) {
            if (!isSpeaking) {
                isSpeaking = true;
                console.log(`🎤 Voice detected (volume: ${volume.toFixed(1)}) - User is speaking`);
                
                // Notify server that user is speaking
                if (socket && socket.connected) {
                    socket.emit('userSpeaking', { speaking: true, job_list_sid });
                }

                // Pause audio if playing
                if (currentAudio && !currentAudio.paused) {
                    currentAudio.pause();
                    console.log('🔇 Audio paused - user speaking');

                    setTimeout(() => {
                        if(isSpeaking) {
                            currentAudio = null;
                            isPlaying = false;
                            console.log('🔇 Audio cleared - user speaking');
                        } else {
                            currentAudio.play().catch(e => {
                                console.error('Error resuming audio:', e);
                            });
                            console.log('🔊 Audio resumed');
                        }
                    }, SILENCE_DELAY + 3000);
                }
            }
            
            // Clear silence timer
            if (silenceTimer) {
                clearTimeout(silenceTimer);
                silenceTimer = null;
            }
        }

        // Updated silence detection handler
        function onSilenceDetected(volume) {
            // Only process if user was speaking
            if (isSpeaking && !silenceTimer) {
                silenceTimer = setTimeout(() => {
                    isSpeaking = false;
                    const applicantLayers = document.querySelectorAll('.applicant .layer-1, .applicant .layer-2, .applicant .layer-3');
                    // Reset layers when not speaking
                    applicantLayers.forEach(layer => {
                        layer.style.transform = 'translateY(-50%) scale(0.5)';
                        layer.style.transition = 'transform 0.08s cubic-bezier(0.4, 0, 0.2, 1)';
                    });

                    console.log(`🔇 Silence detected (volume: ${volume.toFixed(1)}) - User stopped speaking`);
                    
                    // Notify server that user stopped speaking
                    if (socket && socket.connected) {
                        socket.emit('userSpeaking', { speaking: false, job_list_sid });
                    }
                    silenceTimer = null;
                }, SILENCE_DELAY);
            }
        }

        // Setup audio recording function. After microphone connection start Websocket
        function setupAudioRecording() {
            // Request access to the microphone
            navigator.mediaDevices.getUserMedia({
                audio: true
            })
            .then(async (stream) => {
                hideMicrophonePopup();

                await setupSocketConnection();
                
                console.log('Got microphone access!');
                audioStream = stream
                // Store the stream for later use
                window.audioStream = audioStream;

                initAudioContext();

                // Microphone Speaking Detection and Frequency Tracking
                detectSpeaking(stream);

            })
            .catch(error => {
                console.error('Error accessing microphone:', error);

                // Update popup content to show error
                const popupContent = document.querySelector('.microphone-popup-content');
                popupContent.innerHTML = `
                    <h3>❌ Microphone Access Denied</h3>
                    <p>Please allow microphone access in your browser settings and refresh the page to start the interview.</p>
                    <button onclick="location.reload()">Refresh Page</button>
                `;
            });
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

            // Reset applicant layers
            const applicantLayers = document.querySelectorAll('.applicant .layer-1, .applicant .layer-2, .applicant .layer-3');
            applicantLayers.forEach(layer => {
                layer.style.transform = 'translateY(-50%) scale(0.5)';
                layer.style.transition = 'transform 0.3s ease-out';
            });

            // Stop frequency analysis
            stopFrequencyAnalysis(); // For AI audio
        }

        // Check Microphone Permission Status
        async function checkMicrophonePermission() {
            try {
                // Check if getUserMedia is supported
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    console.log('getUserMedia not supported');
                    return false;
                }

                // Try to get microphone access without showing permission prompt
                const permissionStatus = await navigator.permissions.query({ name: 'microphone' });
                
                if (permissionStatus.state === 'granted') {
                    console.log('Microphone permission already granted');
                    hideMicrophonePopup();
                    setupAudioRecording();
                    return true;
                } else {
                    console.log('Microphone permission not granted:', permissionStatus.state);
                    showMicrophonePopup();
                    return false;
                }
            } catch (error) {
                console.log('Error checking microphone permission:', error);
                return false;
            }
        }

        checkMicrophonePermission();
    });
</script>