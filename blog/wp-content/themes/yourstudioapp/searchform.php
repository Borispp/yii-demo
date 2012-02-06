<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' )?>" >
    <div>
        <input type="text" value="<?php echo get_search_query(); ?>"  name="s" id="s" placeholder="Type Your Keywords Here&#133;" />
        <input type="submit" id="searchsubmit" value=" " />
    </div>
</form>