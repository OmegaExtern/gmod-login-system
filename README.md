# gmod-login-system
Experimental PHP/MySQL-based login system for Garry's Mod.

# INSTALLATION
These steps have been tested and assume you are running a Windows OS.
First of all, I recommend you read all instructions carefully, before and while actually doing this.
It is **very important** to follow every step in exact order **as written**, not doing so may corrupt parts of XAMPP or login system won't work as expected.
Second, download master repository (link provided below) and extract it on your computer.
However, you could also clone master repository on your desktop using [Git](https://git-scm.com/downloads) or [GitHub for Windows](https://windows.github.com/), too.
These instructions may get out-of-date as time advances, I will try to keep them up-to-date for as long as I work on this project.
Other information that could help sometime:
I'm running Windows 10 (64-bit), most recent XAMPP build as of 19th of June 2015, that is 5.6.8 which was released on 21st of April 2015. If you have newer XAMPP/PHP/phpMyAdmin/PhpStorm installed, this should still work fine.
I use [Notepad++](https://notepad-plus-plus.org/download/) text editor for editing INI files. [Google Chrome](https://www.google.com/chrome/browser/desktop/index.html) (43.0) as web-browser. PhpStorm 8.0.3 which is built on 12th February 2015. Xdebug version is 2.3.3.

## Requirements
* [Repository](https://github.com/OmegaExtern/gmod-login-system/archive/master.zip)
* [Garry's Mod](http://www.garrysmod.com/)
* [XAMPP](https://www.apachefriends.org/index.html)
* [PhpStorm](https://www.jetbrains.com/phpstorm/)

## Configuring XAMPP
Assuming you have installed XAMPP on your computer; let the fun begin.
### Updating PHP & phpMyAdmin
1. [Download PHP 5.6.10 VC11 x86 Thread Safe](http://windows.php.net/download/).
2. [Download phpMyAdmin 4.4.10](http://www.phpmyadmin.net/home_page/downloads.php).
3. Stop XAMPP modules/services/localhost server using XAMPP control panel, click on `Stop` action button for each module.
4. Using File Explorer navigate to the XAMPP installation directory (by default `C:\xampp\`).
5. Create a copy of `php` folder (rename it to `php-backup`).
6. Create a copy of `phpMyAdmin` folder (rename it to `phpMyAdmin-backup`).
7. Wait for the first download to complete. Extract contents from an archive file downloaded from the first step, into `C:\xampp\php\` directory (do overwrite existing files).
8. Wait for the second download to complete. Extract contents from an archive file downloaded from the second step, into `C:\xampp\phpMyAdmin\` directory.
9. Note, about phpMyAdmin archive, they put files into a folder inside an archive, what you should do is extract files from that folder (do overwrite existing files when asked).
10. Navigate to `C:\xampp\php\` directory.
11. Rename `php.ini-development` file to `php.ini`. Show file extensions by un-checking "Hide extensions for known file types" in File Explorer Options dialog (this setting can be changed much easier way in W10; just Google/YouTube if you get stuck on this step).
12. Open `php.ini` file using a text editor (preferably, with INI file format support for easier editing).
13. Search for `extension_dir` (near line 736), you want to assign this key to point to ext folder which is by default located at `C:\xampp\php\` directory. Line should look like this: `extension_dir = "C:\xampp\php\ext"`.
14. Search for `; Windows Extensions` (near line 872), below comments is a list of extensions, you have to uncomment them, to do that, keep removing `;` character in-front of each line which starts with `;extension` until a first blank line.
15. Do not quit XAMPP or text editor where you have opened `php.ini`, yet. **Continue reading/doing in the order as written**.

### Debugging the system (part 1)
Doing this here to save time later.<br>
- In text editor where you have opened `php.ini` file, scroll to the end, append a new line, and then append this (copy/paste):
```
[XDebug]
zend_extension="C:\xampp\php\ext\php_xdebug.dll"
xdebug.remote_enable=1
xdebug.remote_host=127.0.0.1
xdebug.remote_port=9000
xdebug.idekey=PHPSTORM
```
- Save changes to `php.ini` file.
- [Download XDebug for PHP 5.6 VC11 TS (32 bit)](http://xdebug.org/download.php). Wait for download to complete.
- Move downloaded DLL file (3rd step) to `C:\xampp\php\ext\` directory.
- Rename DLL file to `php_xdebug` (do not erase extension, it should remain DLL).
- If you install modules as services you won't need to start XAMPP control panel every time. Stop `Apache` and `MySQL` modules if they are running. Click on red 'X' icon before module names, it should ask you to confirm an action, click `Yes` button, do this for `Apache` and `MySQL` modules.
- Now click on `Start` action button for `Apache` and `MySQL` modules.
- Proceed next once the modules are up and running, that is when the background color of the module names turns green, and port numbers gets displayed. Apache ports: 80, 443. MySQL port: 3306. 

## Configuring MySQL
[Navigate to phpMyAdmin](http://127.0.0.1/phpmyadmin/) page, wait for it to load, if you see anything like "phpMyAdmin - Error" and the rest of the page is blank, like:<br>
![Extension is missing](http://i.imgur.com/pwHahph.png)<br>
Before fixing error(s), stop `Apache` and `MySQL` modules.
This is because, either you have downloaded non-threadsafe PHP (read the first step at `Updating PHP & phpMyAdmin`, it explicitly says **Thread Safe**), or you have skipped steps (10+).
If you don't see any errors, congratulations, proceed next.
Default credentials to access phpMyAdmin (for XAMPP) are, username: `root`, leave the password field blank/empty and click on `Go` button. Try the following JavaScript I wrote, it will automatically log in as you execute it while on phpMyAdmin login page.
```javascript
(function() {
  if (window.location.href.match(/^http(s)?:\/\/(127.0.0.1|localhost)\/phpmyadmin\/$/) === null) {
    alert("Attempting to navigate to phpMyAdmin... Evaluate JS once phpMyAdmin login page loads.");
    window.location = "http://127.0.0.1/phpmyadmin/";
    return;
  }
  var input_username = document.getElementById("input_username");
  if (input_username === null) {
    return;
  }
  var input_password = document.getElementById("input_password");
  if (input_password === null) {
    return;
  }
  var input_go = document.getElementById("input_go");
  if (input_go === null) {
    return;
  }
  input_username.value = "root";
  input_password.value = "";
  input_go.click();
})();
```
Once you login, you may notice `You are connected as 'root' with no password...` error which appears at the very bottom of the page; you can ignore this message (hide it using AdBlock or by writing JS that will inject into page with Tampermonkey), in fact you should (otherwise if you wish to have a password, you will need to update constant variable `DATABASE_PASSWD` [here](https://github.com/OmegaExtern/gmod-login-system/blob/master/Constants.php#L11)).

### Importing a database
1. Click on `phpMyAdmin` logo on the upper-left, click on `SQL` tab.
2. Go [here](https://raw.githubusercontent.com/OmegaExtern/gmod-login-system/master/system.sql), select all (Ctrl+A), and copy it to the clipboard (Ctrl+C).
3. Go back to SQL in phpMyAdmin, focus a query box and paste clipboard text (Ctrl+V).
4. Click on the `Go` button. Wait for the response. To verify, click on `Databases` tab, if you see `gmod_login_system` on the server databases list then MySQL setup is completed.

## Configuring PhpStorm
### PHP interpreter
1. Run PhpStorm. You should see `Quick Start` window, if not then click on File menu, and then click on `Close Project` button. Once you are at `Quick Start` window, click on `Configure` and then click `Settings` to manage settings.
2. On the left pane, expand `Languages & Frameworks` tree and then click on `PHP` sub-tree.
3. On the right-hand side, change `PHP language level` setting to `5.6 (variadic functions, argument unpacking, etc.)`.
4. Click on the `...` button to manage PHP interpreter. In the new window, click on `+` sign button in the upper-left, `Select Interpreter Path` menu shows up, click on `Other Local...` button.
5. In the new window, under `General` on the right-hand side, click on the `...` button to choose PHP executable (by default it is located at `C:\xampp\php\php.exe`).
6. Click on `OK` to check PHP installation, after that it should recognize version of PHP and debugger (Xdebug). Click on `Apply` and then click on the `OK` button. Finally, click on `OK` button to save settings.
7. In upper-left corner of the `Quick Start` window, click on '<=' (back) arrow and then click on `Open...` button to open gmod-login-system project. In the new window, select the master repository directory which you have extracted/cloned onto your computer (it should display PhpStorm icon for that folder) and then click on the `OK` button to open the project.

### Database / Data Sources & Drivers
- Once the project has been loaded, you need to open Database window. There are several ways to do that, press the key Alt (for Windows/*nix) or Command (for Mac) twice and keep it down. While the key is pressed, the tool window buttons are visible. Click on `Database` (on the right side). You can stop pressing the key you were holding. You could have done this by using menu-bar, go to `View`, hover over `Tool Windows`, and then click on `Database` button.
- In upper-left corner of the `Database` window, click on `+` sign button, hover over `Data Sources`, and then click on `MySQL` button.
- In the new window that shows up, on the left pane, expand `Drivers` tree and select `MySQL` sub-tree. And then on the right-hand side, select `Settings` tab, expand `Driver files`, you should see text which says `Click to download files`, click on the `download` link. IDE will download MySQL Connector/J files for you. Wait for it to complete. Once it is complete, you should see some files being added at `Driver files`.
- On the left pane expand `Data Sources` tree, click on the (first sub-tree) `MySQL - @localhost`. On the right-hand side, go to `Database` tab.
Ensure the following settings are assigned as shown in the following table:

| Name | Value |
| ---- | ----- |
| Host | localhost |
| Port | 3306 |
| Database | gmod_login_system |
| User | root |
| Password | |

Password is not set (by default), leave it blank/empty.
- Click on `Test Connection` button to check if PhpStorm can connect to MySQL database. Wait for the response. It should tell you extra details (driver name, version...) and `Connection successful`. Click on the `OK` button.
- If you expand `Driver files`, it should say `Using MySQL driver files`. If not, go back to third step.
- Click on the `Apply` and then click on the `OK` button to save Data Sources & Drivers settings.

## Installation for Garry's Mod
1. Copy `cfg` and `lua` folders from the master repository directory and paste over `Steam install path\SteamApps\common\GarrysMod\garrysmod` (do overwrite files when asked).
2. Repeat the first step every time when you make changes in master repository directory and reconnect to the game/server or recreate it.
3. Run Garry's Mod, create a new (LAN) game/server with your favorite map.
4. Once you are in-game, open Developer Console and submit `lua_openscript_cl main.lua` command. You should see notification in the upper-right corner. Press key **INS** or **Insert** to bring up login system VGUI.
5. If you check out Developer Console, you will see callback information after the player sends a recognizable chat-command/message.

### Available chat commands
Chat commands are case-insensitive (e.g. !LogIN and !login will trigger the same event). But, arguments **are case-sensitive**.

| Command | Arguments | Description | Rank requirement | Cost |
| -------:|:--------- |:----------- |:----------------:|:----:|
| `!login` | | Attempts to log in to the system. | Not implemented. | Not implemented. |
| `!logout` | | Attempts to log out from the system. | Not implemented. | Not implemented. |
| `!register` | `name`: Choose your name, at least 4 chars long (max. 24), must start with a letter, only A-Z/a-z and 0-9 allowed. | Attempts to register a new player with specified name into the system. | Not implemented. | Not implemented. |

**More - Coming soon**!!!

## Debugging the system (part 2)
I prefer to use Google Chrome web-browser for the first time, later, for debugging just use web-browser of your choice (preferably, Gecko based).
- Go back to PhpStorm window.
- In upper-right corner of the IDE, click on the icon which says `Start Listening for PHP Debugger Connections`/`Listen for debugger connections`.
- After you start listening for PHP debugger connections, you should notice that icon has been changed (green on top, before it was red as it wasn't listening..), you need to open `post.php` file. To do that, there are several ways, in upper-left corner (below menu-bar) where it says `gmod-login-system`, click on it. List of project files pops up, click on `post.php` file button. You could also use `Project` window (using menu-bar, go to View, hover over `Tool Windows`, click on `Project` button, and then open `post.php` file).
- Once you have `post.php` file opened, you need to open it in the web-browser. To do that, there are several ways, move your cursor over upper-right corner of the code editor, you should see web-browser icons, click on Google Chrome icon. You could also right click `post.php` file in the Project window, hover over `Open in Browser` and then click on `Chrome` button. Just for the reference, URL should be [http://localhost:63342/gmod-login-system/post.php](http://localhost:63342/gmod-login-system/post.php).
- Once you have opened `post.php` file in Google Chrome web-browser. You need to execute the following JavaScript (press F12 key, and then go to Console, copy/paste the following code and then press Enter/Return key):
```javascript
(function() {
  document.cookie='XDEBUG_SESSION=PHPSTORM;path=/;';
})();
```
- Go insert breakpoints around, in main.php file. Then submit form in web-browser, PhpStorm should notify you upon the request, accept it.
- To stop debugging session, in upper-right corner of the IDE, click on the button which says `Stop Listening for PHP Debugger Connections`. In web-browser, execute the following JS:
```javascript
(function() {
  document.cookie='XDEBUG_SESSION=;expires=Mon, 05 Jul 2000 00:00:00 GMT;path=/;';
})();
```
- You should consider bookmarking JS or [Xdebug helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc) extension for Google Chrome. Now you can debug the system and do all sorts of things. Happy coding.

## YouTube video covering the entire setup
[YouTube channel](https://www.youtube.com/user/OmegaExtern)<br>
**TODO**.

# CONTRIBUTION
This project is still under development. And it is getting better with every update.
Everybody should feel free to fork and contribute to this project.
Please report all bugs, post your ideas/suggestions/problems using the [issue tracking page](https://github.com/OmegaExtern/gmod-login-system/issues/new).
Everything else/contact me:
- [@Facepunch](http://facepunch.com/member.php?u=618174).
- [@Steam](https://steamcommunity.com/profiles/76561198123458027).
- [@Twitter](https://twitter.com/OmegaExtern).
- [@Website](http://omegaextern.tk).

# LICENSE
[The MIT License](http://opensource.org/licenses/MIT)

Copyright (c) 2015 OmegaExtern

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
