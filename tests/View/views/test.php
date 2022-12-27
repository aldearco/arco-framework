@title->(Test Title)

    @styles
        <link rel="stylesheet" type="text/css" href="/style.css">
    @endstyles

    @content
        @method->(PUT)
        <h1><?= $parameter1 ?></h1>
        <h1><?= $parameter2 ?></h1>
    @endcontent

    @scripts
        <script type="text/javascript" src="file.js"></script>
    @endscripts