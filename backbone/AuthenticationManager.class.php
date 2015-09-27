<?php
require_once("Control.class.php");
require_once("Crypto.class.php");

define("LOGIN_SERVICE_ERROR", 0);
define("VALID_LOGIN", 1);
define("LOGIN_REJECTED", 2);
define("ACCOUNT_SUSPENDED", 3);
define("PASSWORD_EXPIRED", 4);

define("REGISTRATION_SERVICE_ERROR", 0);
define("REGISTRATION_VALID", 1);
define("USERNAME_IN_USE", 2);
define("BAD_EMAIL", 3);
define("PASSWORDS_NO_MATCH", 4);
define("EMAIL_IN_USE", 5);
define("BAD_USERNAME", 6);

define("EMAIL_REJECTED", 4);
define("RECOVERY_SERVICE_ERROR", 0);
define("RECOVERY_DISPATCHED", 1);
define("INVALID_TOKEN", 2);
define("RECOVERY_VALID", 3);

define("USERNAME", "user");

@session_start();

class AuthenticationManager {

    public function AuthenticationManager() {
        $this->control = Control::getControl();
        $this->db = $this->control->database;
        $this->utility = $this->control->utility;
        $this->crypto = new Crypto();
    }

    public static $loginResponseMessage = array(
        LOGIN_SERVICE_ERROR => "Error with login service, please try again.",
        VALID_LOGIN => "Welcome {username}, You are now logged in.",
        LOGIN_REJECTED => "Login rejected, please check details and try again.",
        ACCOUNT_SUSPENDED => "This account has been suspended.",
        PASSWORD_EXPIRED => "The password on this account has expired."
    );

    public static $registerResponseMessage = array(
        REGISTRATION_SERVICE_ERROR => "Error with registration service, please try again.",
        REGISTRATION_VALID => "You have successfully registered, a verification email has been sent please follow the enclosed instructions.",
        USERNAME_IN_USE => "This username is unavailable, please make another selection.",
        BAD_EMAIL => "The email field is bad, try again.",
        BAD_USERNAME => "The username is bad(exceeds 40 characters), try again.",
        PASSWORDS_NO_MATCH => "Your passwords do not match.",
        EMAIL_IN_USE => "This email is unavailable for use, please use another."
    );

    public static $recoveryResponseMessage = array(
        RECOVERY_SERVICE_ERROR => "Error with the recovery service, please try again.",
        RECOVERY_DISPATCHED => "A recovery email has been sent, please follow the enclosed instructions.",
        INVALID_TOKEN => "Bad request, thrown out.",
        RECOVERY_VALID => "Your new password has been emailed to you, welcome back.",
        EMAIL_REJECTED => "Your recovery request has been rejected, please try again."
    );

    public function outputLoginForm($username=FALSE, $executePath="") {
        ?>
        <form action="<?php echo $executePath; ?>" method="post">
            <table>
                <?php if(!$username) { ?>
                <tr>
                    <td>Email:</td><td><input style="width:150px;" maxlength="60" name="email" type="text" placeholder="Email" /></td>
                </tr>
                <?php } else { ?>
                    <tr>
                        <td>Username:</td><td><input style="width:150px;" maxlength="60" name="user" type="text" placeholder="Username" /></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Password:</td><td><input style="width:150px;" maxlength="60" name="pass" type="password" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" /></td>
                </tr>
                <tr>
                    <td></td><td><input style="width:150px;" class="btn" type="submit" value="Login" /></td>
                </tr>
            </table>
        </form>
        <?php
    }

    public function outputRegisterForm($executePath="") {
    ?>
        <form action="<?php echo $executePath; ?>" method="post">
            <table>
                <tr>
                    <td><input class="span6" style="width:250px; height:30px;" maxlength="60" name="user" type="text" required="true" placeholder="Username..." /></td><td><input class="span6" style="width:250px; height:30px;" maxlength="60" required="true" name="email" type="text" placeholder="email@email.com" /></td>
                </tr>
                <tr>
                    <td><input class="span6" style="width:250px; height:30px;" maxlength="60" required="true" name="pass" type="password" placeholder="password" /></td><td><input class="span6" style="width:250px; height:30px;" maxlength="60" required="true" name="passc" type="password" placeholder="confirm password" /></td>
                </tr>
            </table>
            <button type="submit" class="btn">Submit</button>
        </form>
    <?php
    }

    public function outputRecoveryForm($executePath="") {
        ?>
        <form action="<?php echo $executePath; ?>" method="post">
            <table>
                    <tr>
                        <td>Email:</td><td><input style="width:150px;" maxlength="60" name="email" type="text" placeholder="Email" /></td>
                    </tr>
                <tr>
                    <td></td><td><input style="width:150px;" class="btn" type="submit" value="Send Recovery" /></td>
                </tr>
            </table>
        </form>
    <?php
    }

    public function sendRecovery() {
        $email = $_POST["email"];

        $emailCheckSql = "SELECT COUNT(*) FROM backbone_users WHERE email= ?";
        $stmt = $this->db->prepare($emailCheckSql);
        $stmt->execute(array($email));
        $data = $stmt->fetch(PDO::FETCH_NUM);

        $validEmail = $data[0] == 0;

        if($validEmail) {
            return EMAIL_REJECTED;
        }
        //$this->control->mailer->sendMail($from, $to, $subject, $body);
    }

    public function recover() {
        //if($_GET["key"] ==)
    }

    public function userExists($username=NULL, $email=NULL) {
        if($username != NULL) {
            $usernameCheckSql = "SELECT COUNT(*) FROM backbone_users WHERE username= ?";
            $stmt = $this->db->prepare($usernameCheckSql);
            $stmt->execute(array($username));
            $data = $stmt->fetch(PDO::FETCH_NUM);

            if ($data[0] > 0) {
                return USERNAME_IN_USE;
            }

            $usernameCheckSql = "SELECT COUNT(*) FROM backbone_users_staging WHERE username= ?";
            $stmt = $this->db->prepare($usernameCheckSql);
            $stmt->execute(array($username));
            $data = $stmt->fetch(PDO::FETCH_NUM);

            if ($data[0] > 0) {
                return USERNAME_IN_USE;
            }
        }

        if($email != NULL) {
            $emailCheckSql = "SELECT COUNT(*) FROM backbone_users WHERE email= ?";
            $stmt = $this->db->prepare($emailCheckSql);
            $stmt->execute(array($email));
            $data = $stmt->fetch(PDO::FETCH_NUM);

            if ($data[0] > 0) {
                return EMAIL_IN_USE;
            }


            $emailCheckSql = "SELECT COUNT(*) FROM backbone_users_staging WHERE email= ?";
            $stmt = $this->db->prepare($emailCheckSql);
            $stmt->execute(array($email));
            $data = $stmt->fetch(PDO::FETCH_NUM);

            if ($data[0] > 0) {
                return EMAIL_IN_USE;
            }
        }

        return -1;
    }

    public function verify() {
        $code = $this->control->utility->getVar("code");

        if($code != "") {
            $insertionSql = "SELECT id FROM backbone_verification WHERE code = ?";

            $stmt = $this->db->prepare($insertionSql);
            if (!$stmt) {
                return REGISTRATION_SERVICE_ERROR;
            }
            $stmt->execute(array($code));

            $data = $stmt->fetch(PDO::FETCH_NUM);
            if(isset($data[0])) {
                $id = $data[0];
                echo "id".$id;
                $insertionSql = "SELECT * FROM backbone_users_staging WHERE id = ?";

                $stmt = $this->db->prepare($insertionSql);
                if (!$stmt) {
                    return REGISTRATION_SERVICE_ERROR;
                }
                $stmt->execute(array($id));

                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $this->db->prepare("INSERT INTO backbone_users (id, email, username, password, i) VALUES (?, ?, ?, ?, ?)");
                if (!$stmt) {
                    return REGISTRATION_SERVICE_ERROR;
                }
                $stmt->execute(array($id, $data["email"], $data["username"], $data["password"], $data["i"]));

                //$this->db->query("INSERT INTO backbone_users (id, email, username, password, i) VALUES (?, ?, ?, ?, ?)");

                $this->db->query("DELETE FROM backbone_verification WHERE id = ".$id);
                $this->db->query("DELETE FROM backbone_users_staging WHERE id = ".$id);

                //TODO send email
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function register() {
        $username = $this->utility->toAlphaNumeric($_POST[USERNAME]);
        $email = $this->utility->toSecureEmailAddress($_POST["email"]);
        $password = $_POST["pass"];
        $passwordc = $_POST["passc"];

        if($password != $passwordc) {
            return PASSWORDS_NO_MATCH;
        }

        if(!$this->utility->isEmailValid($email)) {
            return BAD_EMAIL;
        }

        $exists = $this->userExists($username, $email);

        if($exists != -1) {
            return $exists;
        }
        //seems legit at this point

        $encrypted = $this->crypto->randomHash($password);

        $encryptedPassword = $encrypted[0];
        $encryptedIndex = $encrypted[1];

        $insertionSql = "INSERT INTO backbone_users_staging (email, username, password, i) VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($insertionSql);
        if (!$stmt) {
           return REGISTRATION_SERVICE_ERROR;
        }
        $stmt->execute(array($email, $username, $encryptedPassword, $encryptedIndex));


        //ID

        $insertionSql = "SELECT id FROM backbone_users_staging WHERE email = ?";

        $stmt = $this->db->prepare($insertionSql);
        if (!$stmt) {
            return REGISTRATION_SERVICE_ERROR;
        }
        $stmt->execute(array($email));

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $id = $data["id"];

        $insertionSql = "INSERT INTO backbone_verification (id, code, verified) VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($insertionSql);
        if (!$stmt) {
            return REGISTRATION_SERVICE_ERROR;
        }
        $code = $this->control->utility->generateRandomCharSequence(15);
        $stmt->execute(array($id, $code, 0));

        return REGISTRATION_VALID;
    }

    public function login($usernameMode=FALSE) {
        if($usernameMode) {
            $selector = $this->utility->toAlphaNumeric($_POST["user"]);
            $checkSql = "SELECT username, password, i FROM backbone_users WHERE username= ?";
        } else {
            $selector = $_POST["email"];
            $checkSql = "SELECT username, password, i FROM backbone_users WHERE email= ?";
        }
        $stmt = $this->db->prepare($checkSql);
        $stmt->execute(array($selector));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $password = $_POST["pass"];

        if(!empty($data)) {
            $encryptedPassword = $data["password"];
            $encryptedIndex = $data["i"];
            $challengePassword = $this->crypto->hashString($password, $encryptedIndex);

            if($encryptedPassword === $challengePassword) {
                //AuthenticationManager::startSession();
                $_SESSION['token'] = sha1($data["username"] . apache_request_headers()["User-Agent"]);
                $_SESSION["username"] = $data["username"];
                return VALID_LOGIN;
            } else {
                return LOGIN_REJECTED;
            }
        } else {
            return LOGIN_REJECTED;
        }
    }

    public function logout() {
        $this->destroySession();
    }

    public function isLoggedIn() {
        return isset($_SESSION["username"]) && isset($_SESSION["token"]);
    }

    public static function startSession() {
        if(@session_id() == '' || !isset($_SESSION)) {
            @session_start();
        }
    }

    public static function destroySession() {
        session_destroy();
    }

    public static function verifySession()
    {
        if (isset($_SESSION["username"]) && isset($_SESSION["token"])) { // they are logged in
            $agent = apache_request_headers()["User-Agent"];

            $encrypted = sha1($_SESSION["username"] . $agent);
            //echo $agent . "<br />";
            //echo "[sha!]" . sha1($agent) . "[!sha]";
            if ($encrypted == $_SESSION["token"]) {
               // echo "valid";
               // echo "<br />Token: ".$_SESSION["token"];
                //echo "<br />Challenge: ".$encrypted;
                //echo "<br />Username: ".$_SESSION["username"];
                //verified
            } else {
                AuthenticationManager::destroySession();
                //echo "failed <br />".$encrypted."<br />";
            }
        } else {
            //echo "not logged in";
            //not logged in
        }
     }

    public $control;
    public $db;
    public $utility;
    public $crypto;

}