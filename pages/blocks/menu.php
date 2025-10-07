<div class="mn_st">
    <span class="menu-btn">
        <img src="/images/elements/menu_m.svg" alt="Menu element" class="hamburger"/>
        <img src="/images/elements/menu-close.svg" alt="Menu element" class="closer"/>
    </span>
    <div class="sc_mn_st">
        <div class="wrap_mn">
            <div class="wr_mn_eu">
                <div class="mn_eu"> <?= $GLOBALS['ar_define_langterms']['MSG_ALL_CONT_PERSONAL'] ?> </div>
                <? if (!$Auth->isAuthorized()) { ?>
                    <div class="mn_pers">
                        <a class="mn_a" href="javascript:void(0)"
                           onclick="popup_show( 'auth_popup' )"> <?= $GLOBALS['ar_define_langterms']['MSG_ALL_AUTORIZARE'] ?> </a>
                        <a class="mn_a" href="javascript:void(0)"
                           onclick="popup_show( 'register_popup' )"> <?= $GLOBALS['ar_define_langterms']['MSG_ALL_INREGISTRARE'] ?> </a>
                    </div>

                <? } else { ?>
                    <div class="mn_pers">
                        <a class="mn_a" href="<?= $CCpu->writelink(45) ?>"> <?= $CCpu->writetitle(45) ?> </a>
                        <a class="mn_a" href="<?= $CCpu->writelink(54) ?>"><?= $CCpu->writetitle(54) ?></a>
                        <a class="mn_a" href="<?= $CCpu->writelink(46) ?>"> <?= $CCpu->writetitle(46) ?> </a>
                    </div>
                <? } ?>
            </div>
            <div class="wr_mn_vl">
                <div class="mn_vl">
                    <?= $arr_course[$_SESSION['var_change']] ?>
                </div>
                <div class="mn_vl_ch">
                    <? foreach ($arr_course as $key => $value) {
                        if ($_SESSION['var_change'] == $key) {
                            continue;
                        }
                        ?>
                        <div onclick="select_course( '<?= $key ?>' )" class="valute"> <?= $value ?> </div>
                    <? } ?>
                </div>
            </div>
            <?
            $ArrLangs = get_list_lang_public();
            ?>

            <? if ($ArrLangs['count'] > 1) { ?>
                <div class="wr_mn_lng">
                    <div class="mn_lng" style="text-transform: capitalize"><?= $CCpu->lang ?></div>
                    <div class="mn_lng_ch">
                        <?
                        foreach ($ArrLangs['lang'] as $key => $value) {
                            if ($key == $CCpu->lang) {
                                continue;
                            }
                            ?>
                            <a class="lang" style="text-transform: capitalize"
                               href="<?= $value['href'] ?>"><?= $key ?></a>
                        <? } ?>
                    </div>
                </div>
            <? } ?>
            <div class="clear"></div>
        </div>

        <a href="<?= $defaultLinks['index'] ?>">
            <img src="/images/other/logo.svg" alt="logo"/>
        </a>

        <div class="main_mn">
            <div><a class="mn_pnt <? mact(array(52)) ?>"
                    href="<?= $CCpu->writelink(52) ?>"><?= $CCpu->writetitle(52) ?></a></div>
            <div><a class="mn_pnt <? mact(array(57, 29, 27)) ?>"
                    href="<?= $CCpu->writelink(57) ?>"><?= $CCpu->writetitle(57) ?></a></div>
            <div><a class="mn_pnt <? mact(array(30)) ?>"
                    href="<?= $CCpu->writelink(30) ?>"><?= $CCpu->writetitle(30) ?></a></div>
            <div><a class="mn_pnt <? mact(array(47)) ?>"
                    href="<?= $CCpu->writelink(47) ?>"><?= $CCpu->writetitle(47) ?></a></div>
            <div class="mn_pnt_litt">
                <div><a href="<?= $CCpu->writelink(56) ?>"
                        class="mn_pnt_ltl_a <? mact(array(56)) ?>"><?= $CCpu->writetitle(56) ?></a></div>
                <? /*
                <div><a href="<?=$CCpu->writelink(32)?>" class="mn_pnt_ltl_a <?mact(array(32))?>"><?=$CCpu->writetitle(32)?></a></div>

                <div><a href="<?=$CCpu->writelink(33)?>" class="mn_pnt_ltl_a <?mact(array(33))?>"><?=$CCpu->writetitle(33)?></a></div>
                */ ?>
                <div><a href="<?= $CCpu->writelink(34) ?>"
                        class="mn_pnt_ltl_a <? mact(array(34)) ?>"><?= $CCpu->writetitle(34) ?></a></div>
            </div>
        </div>
    </div>
    <div class="vcard">
        <div class="wr_src">
            <div class="info_txt">
                <div class="phn_f">
                    <a class="tel" href="tel:<?
                    $tel = preg_replace(['/\s/', '/\(/', '/\)/', '/-/'], '', $GLOBALS['ar_define_settings']['FOOTER_NUM']);
                    echo $tel;
                    ?>">
                        <?= $GLOBALS['ar_define_settings']['FOOTER_NUM'] ?>
                    </a>
                </div>
                <?= $Main->GetPageIncData('TEXT_ADDRESS', $CCpu->lang) ?>
            </div>
            <a href="<?= $CCpu->writelink(51) ?>" class="src_btn">
                <span><?= $CCpu->writetitle(51) ?></span>
            </a>
        </div>
    </div>
</div>