<div id="uploadContainer">
    
    <?php
    
    if (isset($this->messages) && count($this->messages) > 0) {
        foreach($this->messages as $key => $message) {
            echo "<p style=\"font-size: 23px; color: " . ($key == "gameCreated" ? "lightgreen" : "crimson") . ";\">" . $message . "</p><br>";
        }
    }
    
    ?>
    
    <h1>Upload your own game!</h1>
    <form method="POST" action="<?= URL ?>upload/upload" enctype="multipart/form-data">
        <h2>Upload section</h2>
        <p style="padding-left: 125px; font-size: 20px; color: #FFCD00;">WARNING: Please make sure your game files are formatted correctly<br> before
            uploading them! Refer to the instructions on this page.</p>
        <div id="formContent">
            <label for="gameName">Name</label>
            <input type="text" name="gameName" id="gameName" placeholder="The name of your game" required><br>
            <label for="gametags">tags</label>
            <input type="text" name="gametags" id="gametags" placeholder="Divide each tags with a space" required><br>
            <label for="gameAuthor">Creator</label>
            <input type="text" name="gameAuthor" id="gameAuthor" placeholder="If left empty, account username will be used" required><br>
            <label for="gameDesc">Description</label>
            <textarea type="text" name="gameDesc" id="gameDesc" placeholder="The description of your game" required></textarea><br>
            <label for="gameBackground">Background</label>
            <input type="file" name="gameBackground" accept=".png" id="gameBackground"><br>
            <label for="gameFile">Game files</label>
            <input type="file" name="gameFile" id="gameFile" required><br>
            <input type="submit" name="submit" id="submit" value="Upload">
        </div>
    </form>
    
    <div id="formInfo">
        <h1>Additional upload information</h1>
        <h2>General</h2>
        <h3>Once a game is uploaded, you cannot edit it's properties. To do this you must<br>
            remove your game and then re-upload.</h3>
        <br>
        <h2>Name</h2>
        <h3>This is the name of your game, which you cannot edit, so make it count.<br>
            <br>
            E.g. "The Tale of the Great Spatula"</h3>
        <br>
        <h2>tags</h2>
        <h3>The tags of your game is very important when searching or filtering.<br>
            You can add multiple "tags" by adding a space in between them.<br>
            <br>
            E.g. "FPS Action Cartoon" would show as "Action / Cartoon / FPS"</h3>
        <br>
        <h2>Creator</h2>
        <h3>You can either name the creator of this game (if it's not yours), however<br>
            if left empty your username will be used.<br>
            <br>
            E.g. "Tarzan the Great"</h3>
        <br>
        <h2>Description</h2>
        <h3>Your game's description is also very important, since this will be where<br>
            you "sell" your games to others. Make it as clear and fun-sounding as possible!<br>
            <br>
            E.g "This game uses a custom dynamic terrain generating technique (much like Minecraft) and...."</h3>
        <br>
        <h2>Background & Game files</h2>
        <h3>The background you select is going to be used in the Stedi App but also<br>
            here on the games list page! So choose carefully. The image resolution has to be in<br>
            between 1024x576 and 1920x1080.<br>
            This means that if you select a white background, nobody will be able to read any game info.<br>
            <br>
            As for the game files, you NEED to name your game executable "game.exe" and it's path has to<br>
            be the root directory of your game.<br>
            <br>
            E.g.
            <img style="vertical-align: middle;" src="../../public/images/gameRootDir.png" alt="Game root directory"></h3>
    </div>
    
    
</div>