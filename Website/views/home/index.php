<div id="formContainer">
    
    <?php 
    
    $username = "";
    $password = "";
    
    if (isset($this->message)) { echo $this->message; }
    if (isset($this->login)) {
        $username = $this->login["username"];
        $password = $this->login["password"];
    }
    
    ?>
    
    <div id="loginContainer">
        <h2>LOGIN</h2>
        <form method="POST" action="<?= URL ?>login">
            <input required type="text" name="username" id="iUsername" value="<?= $username ?>" placeholder="Username" autofocus><br>
            <input required type="password" name="password" id="iPassword" value="<?= $password ?>" placeholder="Password">
            <input type="submit" id="iSubmit" hidden="true">
        </form>
    </div>
    
    <div id="separator"></div>
    
    <div id="registerContainer">
        <h2>REGISTRATION<h2>
        <form method="POST" action="<?= URL ?>login/register">
            <input required type="email"     name="email"     id="iEmail"     placeholder="E-mail"><br>
            <input required type="text"      name="username"  id="iUsername"  placeholder="Username"><br>
            <input required type="password"  name="password"  id="iPassword"  placeholder="Password"><br>
            <input type="password"  name="hPassword" id="iPassword"  hidden="true"><br>
            <input type="submit" id="iSubmit" hidden="true">
        </form>
    </div>
</div>