<!-- start koins_main_index.tpl -->
<h1><{$smarty.const._KOINS_TITLE_MAIN_INDEX}></h1>
<div><{$smarty.const._KOINS_DESC_MAIN_INDEX}></div>

<{if count($koins.errors) > 0}>
    <div class="errorMsg">
        <ul>
            <{foreach from=$koins.errors item="error"}>
                <li><{$error}></li>
            <{/foreach}>
        </ul>
    </div>
<{/if}>

<form action="<{$koins.url}>/index.php" method="get">
    <table class="outer" cellspacing="1" cellpadding="4">
        <tr>
            <th colspan="2"><{$smarty.const._KOINS_TITLE_MAIN_INDEX}></th>
        </tr>
        <tr>
            <td class="head" width="90"><{$smarty.const._KOINS_PLATE}></td>
            <td class="<{cycle values="odd,even"}>">
                <{foreach from=$koins.plates item="plate"}>
                    <div style="width:155px; float:left; padding:0 0px 5px 0;">
                        <{strip}>
                            <label>
                                <input type="radio" name="plate" id="plate" value="<{$plate.name}>" <{if $koins.params.plate == $plate.name}>checked="checked"<{/if}>>
                                <img src="<{$plate.url}>" alt="<{$plate.title}>" width="<{$plate.width}>" height="<{$plate.height}>" style=" vertical-align:middle;" onclick="document.getElementById('plate_<{$plate.name}>').click();">
                            </label>
                        <{/strip}>
                    </div>
                <{/foreach}>
            </td>
        </tr>
        <tr style="clear:left">
            <td class="head"><{$smarty.const._KOINS_ICON}></td>
            <td class="<{cycle values="odd,even"}>">
                <{foreach from=$koins.icons item="icon"}>
                    <div style="width:51px; float:left; padding:0 0px 5px 0;">
                        <{strip}>
                            <label>
                                <input type="radio" name="icon" id="icon" value="<{$icon.name}>" <{if $koins.params.icon == $icon.name}>checked="checked"<{/if}>>
                                <img src="<{$icon.url}>" alt="<{$icon.title}>" width="<{$icon.width}>" height="<{$icon.height}>" style="background-image:url(<{$koins.url}>/images/icon_back.png); vertical-align:middle;" onclick="document.getElementById('icon_<{$icon.name}>').click();">
                            </label>
                        <{/strip}>
                    </div>
                <{/foreach}>
                <ul style="clear:left">
                    <li>
                        <small><{$smarty.const._KOINS_XCL_NO_APPLY_ICON}></small>
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="head"><{$smarty.const._KOINS_TITLE}></td>
            <td class="<{cycle values="odd,even"}>">
                <div>
                    <{$smarty.const._KOINS_UPLINE}> :
                    <input type="text" name="upline" value="<{$koins.params.upline}>">
                </div>
                <div>
                    <{$smarty.const._KOINS_LOWLINE}> :
                    <input type="text" name="lowline" value="<{$koins.params.lowline}>">
                </div>
                <ul>
                    <li>
                        <small><{$smarty.const._KOINS_INPUT_NAME}></small>
                    </li>
                    <li>
                        <small><{$smarty.const._KOINS_XOOPS2_ONLY_UPLINE}></small>
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="head"><{$smarty.const._KOINS_IMG_TYPE}></td>
            <td class="<{cycle values="odd,even"}>">
                <label>
                    <input type="radio" name="img_type" id="img_type" value="png" <{if $koins.params.img_type == 'png'}>checked="checked"<{/if}>>
                    <{$smarty.const._KOINS_PNG}>
                </label>
                <label>
                    <input type="radio" name="img_type" id="img_type" value="gif" <{if $koins.params.img_type == 'gif'}>checked="checked"<{/if}>>
                    <{$smarty.const._KOINS_GIF}>
                </label>
            </td>
        </tr>
        <{if $koins.newicon}>
            <tr>
                <td class="head"><{$smarty.const._KOINS_SAMPLE}></td>
                <td class="<{cycle values="odd,even"}>">
                    <img src="<{$koins.newicon.url}>">
                </td>
            </tr>
        <{/if}>
        <tr>
            <td class="head"><{$smarty.const._KOINS_CONTROL}></td>
            <td class="<{cycle values="odd,even"}>">
                <input type="submit" value="<{if !$koins.generated}><{$smarty.const._KOINS_GENERATE}><{else}><{$smarty.const._KOINS_REFLESH}><{/if}>">
                <{if $koins.generated}>
                    <input type="submit" name="download" value="<{$smarty.const._KOINS_DOWNLOAD}>">
                    <{if $xoops_isadmin}>
                        <input type="submit" name="apply2module" value="<{$smarty.const._KOINS_APPLY_TO_MODULE}>">
                    <{/if}>
                <{/if}>
            </td>
        </tr>
    </table>
</form>
<!-- end koins_main_index.tpl -->
