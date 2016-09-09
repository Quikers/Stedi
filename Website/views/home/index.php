<div id="formContainer">
    <div id="loginContainer">
        <h2>LOGIN</h2>
        <form method="POST" action="<?= URL ?>login">
            <input required type="text" name="username" id="iUsername" placeholder="Username"><br>
            <input required type="password" name="password" id="iPassword" placeholder="Password">
        </form>
    </div>
    
    <div id="separator"></div>
    
    <div id="registerContainer">
        <h2>REGISTRATION<h2>
        <form method="POST" action="<?= URL ?>register">
            <input required autocomplete="off" type="email"     name="email"     id="iEmail"     placeholder="E-mail"><br>
            <input required autocomplete="off" type="text"      name="username"  id="iUsername"  placeholder="Username"><br>
            <input required autocomplete="off" type="password"  name="password"  id="iPassword"  placeholder="Password"><br>
            <input required autocomplete="off" type="text"      name="firstname" id="iFirstname" placeholder="First name"><br>
            <input          autocomplete="off" type="text"      name="insertion" id="iInsertion" placeholder="Insertion (e.g. van der)"><br>
            <input required autocomplete="off" type="text"      name="lastname"  id="iLastname"  placeholder="Last name">
        </form>
    </div>
</div>