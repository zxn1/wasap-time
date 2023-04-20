<div class="container">
@if($session != null)
<head>
	<title>Wasap</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 16px;
			color: #333;
			background-color: #f2f2f2;
		}
	</style>
    <link rel="stylesheet" href="/resources/css/chat.css">
</head>
<body>
	<div class="chat">
		<div class="message received">
			<div class="avatar"></div>
			<div class="text">
				<p>Hello! How are you?</p>
				<div class="time">10:30 AM</div>
			</div>
		</div>
		
		<div class="message sent">
        <div class="avatar"></div>
			<div class="text">
				<p>Hey, I'm good! How about you?</p>
				<div class="time">10:35 AM</div>
			</div>
		</div>
		
		<div class="message received">
			<div class="avatar"></div>
			<div class="text">
				<p>I'm doing great, thanks for asking!</p>
				<div class="time">10:40 AM</div>
			</div>
		</div>
		
		<div class="message system">
			<div class="text">
				<p>John has joined the chat.</p>
			</div>
		</div>
		
		<div class="message sent">
			<div class="avatar"></div>
			<div class="text">
				<p>That's great to hear!</p>
				<div class="time">10:45 AM</div>
			</div>
		</div>
	</div>
</body>
@endif
</div>