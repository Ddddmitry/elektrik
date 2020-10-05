<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
<section class="catalog">
    <div class="container">

        <div class="catalog__content">
            <h1 class="main__headline main__headline--center main__headline--red">
                Все специалисты, <a href="#city_change" data-fancybox="" data-src="#city_change" class="link-red">г. <?=$arResult["USER_CITY"]["NAME"]?></a>
            </h1>
            <div class="catalog__header">
                <div class="btn-filter">Фильтры и сортировка</div>
                <div class="catalog__header-drop">
                    <div class="catalog__lside">
                        <div class="catalog__sorting">
                            <div class="catalog__sorting-item <?=($arResult["SORT"]["BY"] == "price")? "is-active" :""?> <?=$arResult["SORT"]["ORDER"]?> js-sort" data-by="price" data-order="<?=$arResult["SORT"]["ORDER"]?>">По цене</div>
                            <div class="catalog__sorting-item <?=($arResult["SORT"]["BY"] == "rating")? "is-active" :""?> <?=$arResult["SORT"]["ORDER"]?> js-sort" data-by="rating" data-order="<?=$arResult["SORT"]["ORDER"]?>">По рейтингу</div>
                            <div class="catalog__sorting-item <?=($arResult["SORT"]["BY"] == "time")? "is-active" :""?> <?=$arResult["SORT"]["ORDER"]?> js-sort" data-by="time" data-order="<?=$arResult["SORT"]["ORDER"]?>">По времени реагирования</div>
                        </div>
                    </div>
                    <div class="catalog__rside">
                        <div class="catalog__checkboxes">
                            <div class="catalog__checkbox">
                                <label for="check-1" class="field-checkbox-on">
                                    <input type="checkbox" name="" id="check-1" class="js-checkbox-sert">
                                    <span class="field-checkbox-on__mark"></span>
                                    <span class="field-checkbox-on__title">Только сертифицированные</span>
                                </label>
                            </div>
                            <div class="catalog__checkbox">
                                <label for="check-2" class="field-checkbox-on">
                                    <input type="checkbox" name="" id="check-2" class="js-checkbox-free">
                                    <span class="field-checkbox-on__mark"></span>
                                    <span class="field-checkbox-on__title">Только свободные</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="catalog__cards js-marketplace-list" data-page="1">
<?endif;?>
    <?if($arResult["NOT_FOUND"]):?>
        <div class="marketList__count">Не найдены исполнители по выбранным параметрам</div>
    <?else:?>

                <? foreach ($arResult["ITEMS"] as $arProfile) {?>
                    <div class="catalog__card">
                        <div class="catalog__card-lside">
                            <div class="catalog__card-side">
                                <?
                                $image = SITE_TEMPLATE_PATH."/images/contractor-empty.png";
                                if($arProfile["PREVIEW_PICTURE"]["SRC"])
                                    $image = $arProfile["PREVIEW_PICTURE"]["SRC"];
                                ?>
                                <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>" class="catalog__card-photo" style="background-image: url(<?=$image?>);"></a>
                                <div class="catalog__card-actions catalog__card-actions--desk">
                                    <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>" class="btn btn--xs btn--gray-bg">Анкета</a>
                                    <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>?contacts=Y" class="btn btn--xs btn--red">Связаться</a>
                                </div>
                            </div>
                            <div class="catalog__card-description">
                                <div class="catalog__card-top">
                                    <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>" class="catalog__card-name main__text main__text--md"><?=$arProfile["NAME"]?></a>
                                    <?if($arProfile["SERTIFIED"]):?>
                                    <div class="catalog__card-action">
                                        <div class="btn btn--white-icon btn--white-icon--xs">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/images/logo-e.svg" alt="">
                                            <span>Сертифицирован</span>
                                        </div>
                                    </div>
                                <?endif;?>
                                </div>

                                    <div class="catalog__card-info">
                                        <?if($arProfile["FREE"]):?>
                                            <div class="catalog__card-status is-free">Свободен</div>
                                        <?else:?>
                                            <div class="catalog__card-status is-busy">Занят</div>
                                        <?endif;?>
                                        <?if($arProfile["TIME"]):?>
                                        <div class="catalog__card-time main__text main__text--xs light">Среднее время ответа: <?=$arProfile["TIME"]?> <?=$arProfile["TIME_TEXT"]?></div>
                                        <?endif;?>
                                    </div>
                                    <div class="catalog__card-actions catalog__card-actions--mob">
                                        <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>" class="btn btn--xs btn--gray-bg">Анкета</a>
                                        <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>?contacts=Y" class="btn btn--xs btn--red">Связаться</a>
                                    </div>

                                <div class="catalog__card-summary main__text main__text--sm">
                                    <?if(strlen($arProfile["PREVIEW_TEXT"]) > MARKETPLACE_LIST_REVIEW_TEXT_LIMIT):?>
                                        <?
                                        $arWords = explode(" ",$arProfile["PREVIEW_TEXT"]);
                                        $review_text_word_count = 0;
                                        foreach ($arWords as $word) {
                                            $word_count = strlen($word);
                                            if($review_text_word_count < MARKETPLACE_LIST_REVIEW_TEXT_LIMIT){
                                                $review_text_word_count += $word_count;
                                                echo $word." ";
                                            }
                                        }
                                        ?>
                                        ...
                                    <?else:?>
                                        <?=$arProfile["PREVIEW_TEXT"]?>
                                    <?endif;?>

                                </div>
                                <div class="catalog__card-feedback">
                                    <div class="catalog__card-rating">
                                        <div class="catalog__card-stars">
                                                <?$fRating = floatval($arProfile["RATING"]);?>
                                                <?for($i=1;$i<=5;$i++):?>
                                                    <?$fRRaz = $fRating-$i;?>
                                                    <div class="catalog__card-star">
                                                        <?if( $fRRaz > -1 ):?>
                                                            <?if( $fRRaz < 0 ):?>
                                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red-gray.svg" alt="">
                                                            <?else:?>
                                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                                            <?endif;?>
                                                        <?elseif($fRRaz <= -1):?>
                                                            <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                                        <?endif;?>
                                                    </div>
                                                <?endfor;?>

                                        </div>
                                        <div class="main__text"><?=$arProfile["RATING"]?></div>
                                        <div class="catalog__card-feed">
                                            <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>#reviews" class="btn btn--gray"><?=$arProfile["REVIEWS"]["COUNT_TEXT"]?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="catalog__card-rside">
                            <?//$arFirstService = array_shift($arProfile["SERVICES"]); ?>
                            <div class="main__text"><?=$arProfile["MIN_SERVICE"]?></div>
                            <div class="catalog__card-price main__text main__text--md red">от <?=number_format($arProfile["PRICE"],0,"."," ") ?> ₽</div>


                            <div class="catalog__card-services">
                                <?shuffle($arProfile["SERVICES"]);?>
                                <? foreach ($arProfile["SERVICES"] as $index =>$arService) {?>
                                    <?if($index == 4) break;?>
                                    <div class="catalog__card-service">
                                        <div class="catalog__card-service-title main__text gray-light main__text--xs"><?=$arService["NAME"]?></div>
                                        <div class="catalog__card-service-line"></div>
                                        <div class="catalog__card-service-price main__text main__text--sm">от <?=number_format($arService["PRICE"],0,"."," ") ?> ₽</div>
                                    </div>
                                <?}?>

                            </div>
                            <a href="<?=$arProfile["DETAIL_PAGE_LINK"]?>#services" class="main__text main__text--sm link">Посмотреть все услуги</a>
                        </div>
                    </div>
                <?}?>
    <?endif;?>

<?if(!$arResult["IS_AJAX"]):?>
            </div>
                <div class="catalog__action" id="js-more-button">
                    <button class="btn btn--border-red js-marketList-more" ><span>Показать еще</span></button>
                </div>
        </div>
    </div>
</section>
<?endif;?>

<?if($arResult["IS_LAST_PAGE"]):?>
    <script>
        document.getElementById('js-more-button').style.display = "none";
        document.getElementById('js-more-button').disabled = true;
    </script>
<?else:?>
    <script>
        document.getElementById('js-more-button').style.display = "flex";
        document.getElementById('js-more-button').disabled = false;
    </script>
<?endif;?>
<?/*

{% set isAjax = result['IS_AJAX'] %}
{% set contractorSections = result['ITEMS'] %}
{% set sort = result["SORT"] %}
{% set searchTitle = result["SEARCH_TITLE"] %}
{% set isLastPage = result["IS_LAST_PAGE"] %}
{% set notFound = result["NOT_FOUND"] %}

{% if not isAjax %}



<div class="marketplace">
    <div class="marketplace-inner typicalBlock typicalBlock_two">
        <h1 class="marketplace__title" id="js-search-title">
            {{ searchTitle }}
        </h1>
        <div class="marketSort">
            <div class="marketSort-inner">
                <div class="marketSort__title">
                    Сортировка:
                </div>
                <div class="marketSort-list">

                    <div class="{% if sort["BY"] == "recommend" %}selected {{ sort["ORDER"]|lower }}{% endif %} marketSort-list__single js-sort" data-by="recommend" data-order="{{ sort["ORDER"]|lower }}">
                        <a href="#" class="marketSort-list__single-inner">
                            Рекомендуемые
                            <div class="marketSort-list__single-inner-arrow">
                                <div class="marketSort-list__single-inner-arrow-inner"></div>
                            </div>
                        </a>
                    </div>

                    <div class="{% if sort["BY"]|lower == "price" %}selected {{ sort["ORDER"]|lower }}{% endif %} marketSort-list__single js-sort" data-by="price" data-order="{{ sort["ORDER"]|lower }}">
                        <a href="#" class="marketSort-list__single-inner">
                            По цене
                            <div class="marketSort-list__single-inner-arrow">
                                <div class="marketSort-list__single-inner-arrow-inner"></div>
                            </div>
                        </a>
                    </div>

                    <div class="{% if sort["BY"]|lower == "rating" %}selected {{ sort["ORDER"]|lower }}{% endif %} marketSort-list__single js-sort" data-by="rating" data-order="{{ sort["ORDER"]|lower }}">
                        <a href="#" class="marketSort-list__single-inner">
                            По рейтингу
                            <div class="marketSort-list__single-inner-arrow">
                                <div class="marketSort-list__single-inner-arrow-inner"></div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <div class="marketSort marketSort_mobile">
            <div class="marketSort-inner">
                <div class="search__filter-single">
                    <select name="marketplace-sort" id="marketplace-sort" data-placeholder="Сортировка" class="js-sort-mobile">
                        <option value=""></option>
                        <option data-by="recommend" data-order="desc" value="1" {% if sort["BY"] == "RECOMMEND" %}selected{% endif %}>Сначала рекомендуемые</option>
                        <option data-by="price" data-order="desc" value="2" {% if sort["BY"] == "PRICE" and sort["ORDER"] == "DESC" %}selected{% endif %}>Сначала дорогие</option>
                        <option data-by="price" data-order="asc" value="3" {% if sort["BY"] == "PRICE" and sort["ORDER"] == "ASC" %}selected{% endif %}>Сначала дешевые</option>
                        <option data-by="rating" data-order="desc" value="4" {% if sort["BY"] == "RATING" %}selected{% endif %}>Сначала с хорошим рейтингом</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="marketList js-marketplace-list" data-page="1">

{% endif %}

            {% if notFound %}
                <div class="marketList__count">Не найдены исполнители по выбранным параметрам</div>
            {% else %}

                {% for profile in contractorSections %}

                    <div class="marketList__single">
                        <div class="marketList__single-inner">
                            <div class="marketList__side marketList__side_one">
                                <div class="marketList__img">
                                    {% if (profile["PREVIEW_PICTURE"]["SRC"]) %}
                                        <img src="{{ profile["PREVIEW_PICTURE"]["SRC"] }}" alt="{{ profile["NAME"] }}" class="marketList__img-tag">
                                    {% else %}
                                        <img src="/local/templates/.default/img/contractor-empty.png" alt="{{ profile["NAME"] }}" class="marketList__img-tag">
                                    {% endif %}
                                </div>
                                <div class="marketList__main">
                                    {% if (profile["REVIEWS"]["COUNT"] or profile["RATING"]) %}
                                        <div class="marketList__numbers">
                                            {% if profile["RATING"] %}
                                                {% include './includes/raiting.twig' with {raitNumber: profile["RATING"]} %}
                                                <div class="marketList__numbers-raitNumber">
                                                    {{ profile["RATING"] }}
                                                </div>
                                            {% endif %}
                                            {% if profile["REVIEWS"]["COUNT"] %}
                                                <div class="marketList__numbers-reviews">
                                                    <a href="{{ profile["DETAIL_PAGE_LINK"] }}#master-reviews" class="button2">
                                                        {{ profile["REVIEWS"]["COUNT_TEXT"] }}
                                                    </a>
                                                </div>
                                            {% endif %}
                                        </div>
                                    {% endif %}
                                    <div class="marketList__title">
                                        <a href="{{ profile["DETAIL_PAGE_LINK"] }}">{{ profile["NAME"] }}</a>
                                    </div>
                                    {% if (profile["PROPERTIES"]["ADDRESS"]["VALUE"] and (profile["PROPERTIES"]["TYPE"]["VALUE"] == constant("ENUM_VALUE_CONTRACTORS_TYPE_LEGAL"))) %}
                                        <a class="marketList__geo">
                                            {% include './includes/icon.twig' with {icon: {name: 'geo', class: ''}} %}
                                            <span>
                                                {{ profile["PROPERTIES"]["ADDRESS"]["VALUE"] }}
                                            </span>
                                        </a>
                                    {% endif %}
                                    {% if profile["PREVIEW_TEXT"] %}
                                        <div class="marketList__desc">
                                            {{ profile["PREVIEW_TEXT"] }}
                                        </div>
                                    {% endif %}
                                </div>
                            </div>
                            <div class="marketList__side marketList__side_two">
                                <div class="marketList__info">

                                    <div class="marketList__servPr">
                                        <div class="marketList__service">
                                            {% if profile["SERVICE"] %}
                                                {{ profile["SERVICE"]["NAME"] }}
                                            {% elseif profile["FOUND_SERVICES"] %}
                                                {% for service in profile["FOUND_SERVICES"] %}
                                                    {{ service }}<br>
                                                {% endfor %}
                                            {% endif %}
                                        </div>

                                        <div class="marketList__price">
                                            {% if not profile["SERVICE"] %}
                                                от
                                            {% endif %}
                                            {{ profile["PRICE"]|number_format(0,'.',' ') }} &nbsp;₽
                                        </div>
                                    </div>

                                    {% if (profile["REVIEWS"]["COUNT"] and profile["REVIEWS"]["LAST"]) %}
                                        <div class="marketList__review">
                                            <div class="marketList__review-title">
                                                Последний отзыв:
                                            </div>
                                            <div class="marketList__review-text">
                                                {% set review_text = profile["REVIEWS"]["LAST"]["PREVIEW_TEXT"] %}
                                                {% if review_text|length > constant('MARKETPLACE_LIST_REVIEW_TEXT_LIMIT') %}
                                                    {% set review_text_array = review_text|split(' ') %}
                                                    {% set review_text_word_count = 0 %}
                                                    {% for ta in review_text_array %}
                                                        {% set word_count = ta|length %}
                                                        {% if review_text_word_count < constant('MARKETPLACE_LIST_REVIEW_TEXT_LIMIT') %}
                                                            {% set review_text_word_count = review_text_word_count + word_count %}
                                                            {{ ta }}
                                                        {% endif %}
                                                    {% endfor %}
                                                    ...
                                                {% else %}
                                                    {{ review_text }}
                                                {% endif %}
                                            </div>
                                        </div>
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                {% endfor %}
            {% endif %}

{% if not isAjax %}
        </div>

        <div class="marketplace__button" id="js-more-button">
            <a href="#" class="button js-marketList-more">
                Показать ещё
            </a>
        </div>

    </div>
</div>
{% endif %}
<div class="upWindow js-upWindow"></div>

{% if isAjax %}
    <script>
        document.getElementById('js-search-title').innerHTML = '{{ searchTitle }}';
    </script>
{% endif %}
{% if isLastPage %}
    <script>
        document.getElementById('js-more-button').style.display = "none";
        document.getElementById('js-more-button').disabled = true;
    </script>
{% else %}
    <script>
        document.getElementById('js-more-button').style.display = "block";
        document.getElementById('js-more-button').disabled = false;
    </script>
{% endif %}

*/?>