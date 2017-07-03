<html>

<head>
    <title>{{ $settings['site.title'] }} - Site is not published</title>
</head>

<body>
    <div align="center">
        <a href="{{ $primary_url }}">
			<img src="{{ $primary_url }}/images/website/404-page-notfound-error.jpg" alt="{{ $settings['site.title'] }}" title="{{ $settings['site.title'] }}">
		</a>
        <br />
		Please click <a href="{{ $primary_url }}">here</a>
	</div>
</body>

</html>