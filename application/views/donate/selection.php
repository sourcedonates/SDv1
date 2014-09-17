<div class="container content" role="main">

    <form action="<?php echo base_url("/index.php/donate/payment").$slug?>" method="post">
    <section class="selection">
        <label class="note" for="who">Wie ben je?</label>
        <select name="who" id="who">
            <option selected disabled>kies een naam</option>
            <?php foreach($forum_users as $user):?>
            <option value="<?=$user->name?>"><?=$user->name?></option>
            <?php endforeach; ?>
        </select>

        <select name="who_steamid" id="who_steamid">
            <option selected disabled>kies een SteamID</option>
            <?php foreach($steamids as $id):?>
            <option value="<?=$id->steamid?>"><?=$id->steamid?></option>
            <?php endforeach; ?>
        </select>
        <input class="submit" type="submit" value="proceed  >>" />
    </section>
    </form>
</div> <!-- end container -->