<pre>
<code id="jsJsonCode"></code>
</pre>

<script>
    $('#jsJsonCode').html(
        JSON.stringify(<?=json_encode($adpemployeedata)?>, null, 4)
    );
</script>