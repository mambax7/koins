<form action="<{$koins.url}>/index.php?controller=module&amp;action=confirm" method="post">
    <{securityToken}><{*//mb*}>
    <div class="xoopsConfirm">
        <{$smarty.const._KOINS_SELECT_MODULE}>
    </div>

    <div class="even">
        <select name="dirname">
            <{foreach from=$koins.modules item="module"}>
                <option value="<{$module.name}>"><{$module.name}> - <{$module.title}></option>
            <{/foreach}>
        </select>
    </div>
    <div style="text-align:center;"><input type="submit" value="<{$smarty.const._KOINS_SELECT_AND_NEXT}>"></div>
</form>
