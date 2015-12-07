{extends file='parent:frontend/index/header.tpl'}

{block name='frontend_index_header_javascript_inline' append}
    $('.language--select option[value="{$shopId}"]').attr("selected", "selected");
    $('.language--form').submit();
{/block}