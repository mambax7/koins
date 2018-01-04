<form action="<{$koins.url}>/index.php?controller=apply&amp;action=apply" method="post">
    <{securityToken}><{*//mb*}>
    <div class="xoopsConfirm">
        <{$smarty.const._KOINS_ARE_YOU_SURE_TO_APPLY}>
    </div>

    <table class="outer">
        <tr>
            <td class="head" style="text-align:center;"><{$smarty.const._KOINS_OLD_ICON}></td>
            <td>&nbsp;</td>
            <td class="head" style="text-align:center;"><{$smarty.const._KOINS_NEW_ICON}></td>
        </tr>
        <tr>
            <td class="even" style="width:50%; text-align:center; vertical-align:middle;">
                <{if !$koins.module.icon_exists}>
                    <{$smarty.const._KOINS_ICON_NOT_FOUND}>
                <{else}>
                    <img src="<{$koins.module.icon_url}>">
                <{/if}>
            </td>
            <td style="width:40px; text-align:center; vertical-align:middle;">
                <img src="<{$koins.url}>/images/right.png">
            </td>
            <td class="even" style="width:50%; text-align:center; vertical-align:middle;">
                <img src="<{$koins.new_icon}>">
            </td>
        </tr>
        <tr>
            <td class="odd">
                <{if !$koins.module.icon_exists}>
                    <{$smarty.const._KOINS_NEW_ICON_CREATION}>
                <{else}>
                    <{if $koins.module.is_renamable && !$koins.module.is_d3module}>
                        <input type="checkbox" name="rename_icon" value="1" checked="checked">
                        <{$smarty.const._KOINS_ICON_RENAMED_INTO|sprintf:$koins.module.renamed_old_icon}>
                    <{elseif $koins.module.is_d3module}>
                        <{$smarty.const._KOINS_NEW_ICON_CREATION_IN|sprintf:$koins.module.name}>
                    <{else}>
                        <{$smarty.const._KOINS_ICON_CANT_RENAME}>
                    <{/if}>
                <{/if}>
            </td>
            <td>&nbsp;</td>
            <td class="odd" style="text-align:center;">
                <{if !$koins.module.icon_exists}>
                    <input type="submit" value="<{$smarty.const._KOINS_CREATE_ICON}>">
                <{else}>
                    <input type="submit" value="<{$smarty.const._KOINS_REPLACE_ICON}>">
                <{/if}>
            </td>
        </tr>
    </table>

    <input type="hidden" name="ticket" value="<{$koins.ticket}>">

</form>
