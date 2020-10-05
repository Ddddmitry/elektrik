<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="partners-services__rside-box js-poffers-filter">
    <form action="" data-poffers-filter>
        <div class="partners-services__rside-title main__text">Выберите категорию</div>
        <div class="partners-services__rside-items">
            <input type="hidden" name="type" data-input-type>
            <div class="partners-services__rside-item">
                <button class="btn btn--filter poffers-filter js-filter-poffers-type" data-type="<?=implode(",",array_keys($arResult["FILTER_DATA"]["TYPES"]))?>">Все</button>
            </div>
            <?foreach ($arResult["FILTER_DATA"]["TYPES"] as $id => $type):?>
                <div class="partners-services__rside-item">
                    <button class="btn btn--filter poffers-filter js-filter-poffers-type <?if($id == $_REQUEST["type"]):?>is-active<?endif;?>" data-type="<?=$id?>"><?=$type?></button>
                </div>
            <?endforeach;?>
        </div>
    </form>
</div>


