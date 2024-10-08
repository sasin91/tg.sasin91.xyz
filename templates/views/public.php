<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<base href="<?= BASE_URL ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/trongate.css">
	<link rel="stylesheet" href="css/app.css">
  <title><?= OUR_NAME ?></title>
	<?= $additional_includes_top ?>
</head>
<body>
	<div class="wrapper">
		<header>
			<div id="header-sm">
				<div id="hamburger" onclick="openSlideNav()">&#9776;</div>
				<div class="logo">
					<?= anchor(BASE_URL, WEBSITE_NAME) ?>
                    <span class="online_count badge">0 Online</span>
				</div>
				<div>
					<?= anchor('account', '<i class="fa fa-user"></i>') ?>
					<?= anchor('logout', '<i class="fa fa-sign-out"></i>') ?>
				</div>
			</div>
			<div id="header-lg">
				<div class="logo">
					<?= anchor(BASE_URL, WEBSITE_NAME) ?>
                    <span class="online_count badge">0 Online</span>
				</div>
				<div>
					<ul id="top-nav">
              <li>
                  <?= anchor('blog', '<i class="fa fa-newspaper-o"></i> Blog') ?>
              </li>
              <li>
                <language-selector />
                <form id="language_form" method="get">
                    <label>
                        <i class="fa fa-language"></i>
                        <select name="lang" onchange="document.getElementById('language_form').submit()">
                            <option <?= empty($lang) || $lang === 'da' ? 'selected' : '' ?> value="da">
                                <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-dk" viewBox="0 0 512 512">
                                    <path fill="#c8102e" d="M0 0h512.1v512H0z"/>
                                    <path fill="#fff" d="M144 0h73.1v512H144z"/>
                                    <path fill="#fff" d="M0 219.4h512.1v73.2H0z"/>
                                </svg>
                            </option>
                            <option <?= $lang === 'en' ? 'selected' : '' ?> value="en">
                                <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-us" viewBox="0 0 512 512">
                                    <path fill="#bd3d44" d="M0 0h512v512H0"/>
                                    <path stroke="#fff" stroke-width="40" d="M0 58h512M0 137h512M0 216h512M0 295h512M0 374h512M0 453h512"/>
                                    <path fill="#192f5d" d="M0 0h390v275H0z"/>
                                    <marker id="us-a" markerHeight="30" markerWidth="30">
                                        <path fill="#fff" d="m15 0 9.3 28.6L0 11h30L5.7 28.6"/>
                                    </marker>
                                    <path fill="none" marker-mid="url(#us-a)" d="m0 0 18 11h65 65 65 65 66L51 39h65 65 65 65L18 66h65 65 65 65 66L51 94h65 65 65 65L18 121h65 65 65 65 66L51 149h65 65 65 65L18 177h65 65 65 65 66L51 205h65 65 65 65L18 232h65 65 65 65 66z"/>
                                </svg>
                            </option>
                        </select>
                    </label>
                </form>
            </li>
          </ul>
				</div>
			</div>
		</header>
		<main><?= Template::display($data) ?></main>
	</div>
	<footer>
		<div class="container">
			<!-- it's okay to remove the links and content here - everything is cool (DC) -->
			<div>&copy; <?= date('Y') . ' ' . OUR_NAME ?></div>
			<div><?= anchor('https://trongate.io', 'Powered by Trongate') ?></div>
		</div>
	</footer>
	<div id="slide-nav">
		<div id="close-btn" onclick="closeSlideNav()">&times;</div>
		<ul auto-populate="true"></ul>
	</div>
	<script src="js/app.js"></script>
    <script src="js/utils.js"></script>
    <script src="js/fingerprint.js"></script>
    <script src="js/language-selector.js"></script>
    <script>
        const socket = (function (url) {
            const state = {
                socket: undefined,
                fingerprint: '',
                num_online: 0,
                onopen: [],
                onclose: [],
                onerror: [],
                onmessage: []
            };

            state.onclose.push((event) => {
                debounce(new_websocket, 1000);
            });

            state.onmessage.push((data, event) => {
                switch(data.channel) {
                    case 'state':
                        state.num_online = data.message.num_online;
                    break;

                    case 'user_status':
                        if (data.message.status === 'online') {
                            state.num_online++;
                        } else {
                            state.num_online--;
                        }

                        online_count.forEach((element) => {
                            element.innerHTML = `${state.num_online} Online`;
                        });
                    break;
                    
                    case 'chat_message':
                        // TODO
                        console.log(data.message);
                    break;    
                }
            });

            function new_websocket() {
                const instance = new WebSocket(url);

                instance.onopen = function (event) {
                    for (const handler of state.onopen) {
                        handler(event);
                    }
                };

                instance.onclose = function (event) {
                    for (const handler of state.onclose) {
                        handler(event);
                    }
                };

                instance.onerror = function (event) {
                    for (const handler of state.onerror) {
                        handler(event);
                    }
                };

                instance.onmessage = function (event) {
                    const data = JSON.parse(event.data);

                    for (const handler of state.onmessage) {
                        handler(data, event);
                    }
                };

                return instance;
            }

            return state;
        })(`<?= WEBSOCKET_URL ?>?fingerprint=${fingerprint}&trongateToken=<?= $token ?? '' ?>&user_id=<?= $user_id ?? null ?>`);

        let fingerprint;

        const online_count = document.querySelectorAll('.online_count');
        let num_online = 0;

        let connectionAttempts = 0;
        let socket;
        function connectToWebsocket() {
            socket = new WebSocket(`<?= WEBSOCKET_URL ?>?fingerprint=${fingerprint}&trongateToken=<?= $token ?? '' ?>&user_id=<?= $user_id ?? null ?>`);
            socket.onopen = function(event) {
                console.log("Connection opened:", event);
            };

            socket.onmessage = function(event) {

            };

            socket.onclose = function(event) {
                console.log("Connection closed:", event);

                debounce(connectToWebsocket, 1000);
            };

            socket.onerror = function(error) {
                console.error("WebSocket error:", error);
            };
        
            connectionAttempts++;
        }

        document.addEventListener('DOMContentLoaded', async function () {
            const fingerprintBuffer = await crypto.subtle.digest(
                'SHA-256', 
                new TextEncoder().encode(
                    generateBrowserFingerprint()
                )
            );

            fingerprint = Array.from(new Uint8Array(fingerprintBuffer))
                .map(b => b.toString(16).padStart(2, '0'))
                .join('');

            connectToWebsocket();
        });
    </script>
	<?= $additional_includes_btm ?>
</body>
</html>
