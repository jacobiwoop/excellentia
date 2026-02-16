<!DOCTYPE html>
<html>
<head>
    <title>Visionneuse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>body, html { height:100%; margin:0; }</style>
</head>
<body>
    @yield('content')
    <script>
        // Blocage des téléchargements
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', e => {
            if (e.ctrlKey && ['s','p'].includes(e.key)) e.preventDefault();
        });
    </script>
</body>
</html>