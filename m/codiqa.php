
<html>
<head>
        <title>blog.micayael.com</title>
 
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0rc1/jquery.mobile-1.0rc1.min.css" />
        <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.0rc1/jquery.mobile-1.0rc1.min.js"></script>
    </head>
 
	
<div data-role="page" id="productos" data-add-back-btn="true" data-back-btn-text="Atr&aacute;s">
 
    <div data-role="header">
        <h1>Productos</h1>
        <a href="#home" data-icon="home" data-iconpos="notext" class="ui-btn-right">Home</a>
    </div>
    <!-- end /header -->
 
    <div data-role="content">
        <ul data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="texto a buscar">
 
            <li data-role="list-divider">Categor&iacute;a 1</li>
 
            <li>
                <a href="">
                    Producto 1
                    <span class="ui-li-count">10</span>
                </a>
                <a href="producto.html" data-rel="dialog" data-transition="pop"></a>
            </li>
            <li>
                <a href="">
                    Producto 2
                    <span class="ui-li-count">12</span>
                </a>
                <a href="producto.html" data-rel="dialog" data-transition="pop"></a>
            </li>
            <li>
                <a href="">
                    Producto 3
                    <span class="ui-li-count">5</span>
                </a>
                <a href="producto.html" data-rel="dialog" data-transition="pop"></a>
            </li>
 
            <li data-role="list-divider">Categor&iacute;a 2</li>
 
            <li>
                <a href="">
                    Producto 4
                    <span class="ui-li-count">23</span>
                </a>
                <a href="producto.html" data-rel="dialog" data-transition="pop"></a>
            </li>
            <li>
                <a href="">
                    Producto 5
                    <span class="ui-li-count">4</span>
                </a>
                <a href="producto.html" data-rel="dialog" data-transition="pop"></a>
            </li>
            <li>
                <a href="">
                    Producto 6
                    <span class="ui-li-count">54</span>
                </a>
                <a href="producto.html" data-rel="dialog" data-transition="pop"></a>
            </li>
 
        </ul>
    </div>
    <!-- end /content -->
 
    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <li><a href="productos.html">Productos</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </div>
    </div>
    <!-- end /footer -->
 
</div>
<!-- end #productos -->
</html>