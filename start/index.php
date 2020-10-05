<?php
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<div class="stub" style="background-image: url('/local/templates/electric/img/back-img.png');">
    <div class="stub-inner">
        <div class="stub-logo">
            <div class="stub-logo-img">
                <img src="/local/templates/electric/img/logo-2.svg" alt="" class="stub-logo-img-tag">
            </div>
        </div>
        <div class="stub-text">
            <div class="stub-text-title">
                Сайт находится <br>в разработке
            </div>
            <div class="stub-text-counter">
                <div class="stub-text-counter-numbers">
                    <div class="stub-text-counter-numbers-single">
                        <div class="stub-text-counter-numbers-single-number" id="month">
                            4
                        </div>
                        <div class="stub-text-counter-numbers-single-text" id="month-text">
                            месяца
                        </div>
                    </div>
                    <div class="stub-text-counter-numbers-single">
                        <div class="stub-text-counter-numbers-single-number" id="day">
                            21
                        </div>
                        <div class="stub-text-counter-numbers-single-text" id="day-text">
                            день
                        </div>
                    </div>
                </div>
                <div class="stub-text-counter-separator"></div>
                <p>
                    до открытия сайта
                </p>
            </div>
        </div>
    </div>
</div>
<script>
    var xDate = new Date(2020, 2, 2);
    var currentDate = new Date();
    var substractMilliseconds = xDate.getTime() - currentDate.getTime();
    var substract = new Date(substractMilliseconds);
    var month = substract.getMonth();
    var day = substract.getDate();
    document.getElementById('month').textContent = month;
    document.getElementById('month-text').textContent = 'месяца';
    if (month == 1) {
        document.getElementById('month-text').textContent = 'месяц';
    }
    if (month == 0) {
        document.getElementById('month-text').textContent = 'месяцев';
    }
    document.getElementById('day').textContent = day;
    document.getElementById('day-text').textContent = 'дня';
    var dayArray = day.toString().split('');
    if (dayArray.length == 1) {
        if (dayArray[0] > 4 && dayArray[0] <= 9) {
            document.getElementById('day-text').textContent = 'дней';
        }
        if (day == 1) {
            document.getElementById('day-text').textContent = 'день';
        }
    } else {
        if (dayArray[1] > 4 && dayArray[1] <= 9) {
            document.getElementById('day-text').textContent = 'дней';
        }
        if (dayArray[0] == 1) {
            if (dayArray[1] > 0 && dayArray[1] < 5) {
                document.getElementById('day-text').textContent = 'дней';
            }
        } else {
            if (dayArray[1] == 1) {
                document.getElementById('day-text').textContent = 'день';
            }
        }

        if (dayArray[1] == 0) {
            document.getElementById('day-text').textContent = 'дней';
        }
    }
</script>
<style>
    @font-face {
        font-family: Gilroy;
        font-display: swap;
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Regular/Gilroy-Regular.eot");
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Regular/Gilroy-Regular.eot") format("embedded-opentype"), url("/local/templates/.default/fonts/Gilroy-Regular/Gilroy-Regular.woff") format("woff"), url("/local/templates/.default/fonts/Gilroy-Regular/Gilroy-Regular.ttf") format("truetype"), url("/local/templates/.default/fonts/Gilroy-Regular/Gilroy-Regular.svg#Gilroy-Regular") format("svg");
        font-weight: 400;
        font-style: normal; }

    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        @font-face {
            font-family: Gilroy;
            src: url("/local/templates/.default/fonts/Gilroy-Regular/Gilroy-Regular.svg#Gilroy-Regular") format("svg"); } }

    @font-face {
        font-family: Gilroy;
        font-display: swap;
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Medium/Gilroy-Medium.eot");
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Medium/Gilroy-Medium.eot") format("embedded-opentype"), url("/local/templates/.default/fonts/Gilroy-Medium/Gilroy-Medium.woff") format("woff"), url("/local/templates/.default/fonts/Gilroy-Medium/Gilroy-Medium.ttf") format("truetype"), url("/local/templates/.default/fonts/Gilroy-Medium/Gilroy-Medium.svg#Gilroy-Medium") format("svg");
        font-weight: 500;
        font-style: normal; }

    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        @font-face {
            font-family: Gilroy;
            src: url("/local/templates/.default/fonts/Gilroy-Medium/Gilroy-Medium.svg#Gilroy-Medium") format("svg"); } }

    @font-face {
        font-family: Gilroy;
        font-display: swap;
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Bold/Gilroy-Bold.eot");
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Bold/Gilroy-Bold.eot") format("embedded-opentype"), url("/local/templates/.default/fonts/Gilroy-Bold/Gilroy-Bold.woff") format("woff"), url("/local/templates/.default/fonts/Gilroy-Bold/Gilroy-Bold.ttf") format("truetype"), url("/local/templates/.default/fonts/Gilroy-Bold/Gilroy-Bold.svg#Gilroy-Bold") format("svg");
        font-weight: 600;
        font-style: normal; }

    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        @font-face {
            font-family: Gilroy;
            src: url("/local/templates/.default/fonts/Gilroy-Bold/Gilroy-Bold.svg#Gilroy-Bold") format("svg"); } }

    @font-face {
        font-family: Gilroy;
        font-display: swap;
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Italic/Gilroy-Italic.eot");
        src: local("Gilroy"), url("/local/templates/.default/fonts/Gilroy-Italic/Gilroy-Italic.eot") format("embedded-opentype"), url("/local/templates/.default/fonts/Gilroy-Italic/Gilroy-Italic.woff") format("woff"), url("/local/templates/.default/fonts/Gilroy-Italic/Gilroy-Italic.ttf") format("truetype"), url("/local/templates/.default/fonts/Gilroy-Italic/Gilroy-Italic.svg#Gilroy-Italic") format("svg");
        font-weight: 400;
        font-style: italic; }

    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        @font-face {
            font-family: Gilroy;
            src: url("/local/templates/.default/fonts/Gilroy-Italic/Gilroy-Italic.svg#Gilroy-Italic") format("svg"); } }
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        overflow-x: hidden;
    }
    * {
        box-sizing: border-box;
    }
    .stub {
        min-height: 100vh;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        font-family: 'Gilroy', sans-serif;
    }
    .stub-inner {
         height: 100%;
         min-height: 100vh;
         width: 100%;
         display: flex;
         align-items: flex-start;
         justify-content: space-between;
         flex-direction: column;
     }
    .stub-logo {
        width: 100%;
        max-width: 960px;
        height: 275px;
        position: relative;
        padding: 56px 0 0 135px;
    }
    @media (max-width: 767px) {
        .stub-logo {
            padding-left: 40px;
        }
    }
    .stub-logo:before {
         content: '';
         transform: translate(-20%, -55%) rotate(-16deg);
         background-color: #d70926;
         width: 125%;
         height: 130%;
         position: absolute;
         top: 0;
         left: 0;
         z-index: 10;
     }
    .stub-logo-img {
         z-index: 20;
         position: relative;
    }
    .stub-text {
        width: 100%;
        padding: 0 215px 100px 135px;
        color: #fff;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        text-shadow: 0 0 5px rgba(0,0,0,0.5);
    }
    @media (max-width: 1199px) {
        .stub-text {
            padding-right: 40px;
        }
    }
    @media (max-width: 767px) {
        .stub-text {
            padding-left: 40px;
        }
    }
    .stub-text-title {
         font-size: 80px;
         line-height: 80px;
         font-weight: 600;
         max-width: 570px;
        width: 100%;
        padding: 0 0 36px 0;
     }
    @media (max-width: 767px) {
        .stub-text-title {
            font-size: 60px;
            line-height: 70px;
        }
    }
    .stub-text-counter {
        font-size: 24px;
        line-height: 28px;
        padding: 0 0 36px 0;
    }
    .stub-text-counter-separator {
         width: 211px;
         height: 6px;
         border-radius: 4px;
         border: solid 1px #d70926;
         margin: 0 0 16px 0;
    }
    .stub-text-counter-numbers {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        font-weight: 600;
    }
    .stub-text-counter-numbers-single {
        margin: 0 60px 32px 0;
    }
    .stub-text-counter-numbers-single:last-child {
        margin-right: 0;
    }
    .stub-text-counter-numbers-single-number {
        font-size: 100px;
        line-height: 100px;
    }
    .stub-text-counter-numbers-single-text {
        font-size: 40px;
        line-height: 40px;
    }

</style>
<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
