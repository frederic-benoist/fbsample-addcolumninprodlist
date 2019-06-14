 <th>
    <select name="filter_column_manufacturer" data-toggle="select2">
        <option value="">{l s='Manufacturer' mod='fbsample_addcolumninprodlist'}</option>
        {foreach from=$manufacturers item=manufacturer}
            <option value="{$manufacturer.id_manufacturer}" 
            {if $filter_column_manufacturer == $manufacturer.id_manufacturer} selected="selected"{/if}>
            {$manufacturer.name}
            </option>
        {/foreach}
    </select>
</th>
