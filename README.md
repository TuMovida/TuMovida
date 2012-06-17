¡TuMovida!
==========
> TuMovida es una página web donde se ponen día a día a disposición del público las mejores actividades para la noche e increíbles ofertas para disfrutarlas. Mediante nuestra página se accede también a la compra de entradas para los eventos más importantes.

> TuMovida es una empresa joven, innovadora y con ganas de trabajar. Registrate ahora y empezá a disfrutar de los beneficios.

##Estructura
*¿Cómo funciona el sistema de TuMovida?*
>**/admin**
>
>>Administración del sitio.
>
>
>**/ajax**
>
>>Páginas que se muestran en el sitio.
>
>**/inc**
>
>>Para includes

###Navegación principal dentro del sitio
La navegación del sitio ocurre gracias a AJAX. Vía el archivo `js/navegacion.js` que busca y carga los `PHP` definidos en 
`/ajax/`.

####Nomenclatura de una URL
Como siempre, el dominio (`www.tumovida.com.uy/`) seguido de `#!/` (vease `Crowling`), seguido de el nombre de la página a
mostrar -*por ejemplo, `evento`*-, seguido de `/id` cuyo id define un párametro vía GET al archivo `PHP` en este caso 
`evento.php` cuyo resultado sería el siguiente `evento.php?id=valorDelID` 