<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?= $arResult['FORM_HEADER'] ?>
<div class="form__items js-message js-message-container">

    <div class="form__item">
        <div class="cols-wrapper">
            <div class="cols">
                <?php if ($arResult['isFormErrors'] === 'Y'): ?>
                    <?= $arResult['FORM_ERRORS_TEXT']; ?>
                <?php endif; ?>


                <?php foreach ($arResult['QUESTIONS'] as $code => $question): ?>
                    <?php
                    $fieldType = $question['STRUCTURE'][0]['FIELD_TYPE'];
                    ?>
                    <?php if ($fieldType === 'hidden'): ?>
                        <?= $question['HTML_CODE'] ?>
                        <?php continue ?>
                    <?php elseif ($fieldType === 'textarea'): ?>

                    <?php elseif ($fieldType === 'file'): ?>

                    <?php else: ?>
                        <div class="col">
                            <div class="form__item-field form__item-field--error-absolute">
                                <div class="textfield-wrapper">
                                    <input type="<?= $fieldType ?>"
                                           class="textfield <?= $code === 'phone' ? 'mask-phone-ru' : '' ?>"
                                           name="<?= $code ?>" data-rule-required="true" aria-required="true">
                                    <div class="textfield-placeholder"><?= $question['CAPTION'] ?></div>
                                    <?php if ($question['REQUIRED'] === 'Y'): ?>
                                        <div class="textfield-after color-active">*</div>
                                    <?php endif; ?>
                                </div>
                                <?php if (is_array($arResult['FORM_ERRORS']) && array_key_exists($code, $arResult['FORM_ERRORS'])): ?>
                                    <div class="form__error">
                                        <?= htmlspecialcharsbx($arResult['FORM_ERRORS'][$code]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="form__item form__item--lm">
        <div class="cols-wrapper">
            <div class="cols">
                <div class="col vmiddle">
                    <div class="checkboxes">
                        <div class="checkboxes__inner">
                            <div class="checkbox checkbox--noinput">
                                <?= $arResult['FORM_DESCRIPTION'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col tablet-hide"></div>
                <div class="col vmiddle text-align-right text-align-center-on-mobile">
                    <input type="submit" class="btn btn--medium minw200 active" name="contact_submit"
                           value="<?= $arResult['arForm']['BUTTON'] ?>">
                </div>
            </div>
        </div>
    </div>

    <?= $arResult['FORM_FOOTER'] ?>
</div>
</form>
