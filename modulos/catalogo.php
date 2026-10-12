<?php
include '../config/conexion.php';
include '../includes/header.php';

// Lógica para el buscador (Req 8)
$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$sql = "SELECT * FROM libros WHERE titulo LIKE '%$busqueda%' OR autor LIKE '%$busqueda%' OR genero LIKE '%$busqueda%'";
$resultado = $conexion->query($sql);

// Buscar qué libros ya tiene en favoritos el usuario actual
$favoritos_usuario = [];
if (isset($_SESSION['id_usuario'])) {
    $id_usu = $_SESSION['id_usuario'];
    $fav_query = $conexion->query("SELECT id_libro FROM favoritos WHERE id_usuario = $id_usu");
    while($f = $fav_query->fetch_assoc()){
        $favoritos_usuario[] = $f['id_libro']; // Guardamos los IDs en un arreglo
    }
}
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #6f42c1;">
            <i class="bi bi-journal-bookmark"></i> Catálogo de Libros
        </h2>
    </div>

    <!-- Buscador de libros (Req 8) -->
    <form class="d-flex mb-4 shadow-sm" method="GET" action="catalogo.php">
        <input class="form-control me-2 rounded-pill bg-light border-0 px-4" type="search" name="buscar" placeholder="Buscar por título, autor o género literario..." value="<?php echo htmlspecialchars($busqueda); ?>">
        <button class="btn text-white rounded-pill px-4" style="background-color: #6f42c1;" type="submit"><i class="bi bi-search"></i> Buscar</button>
        <?php if($busqueda != ''): ?>
            <a href="catalogo.php" class="btn btn-outline-secondary rounded-pill ms-2">Limpiar</a>
        <?php endif; ?>
    </form>

    <!-- Cuadrícula de libros -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while($libro = $resultado->fetch_assoc()): ?>
                <div class="col mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 18px; overflow: hidden; background-color: #fcfcfc;">
                        
                        <!-- Contenedor relativo para la imagen y elementos flotantes -->
                        <div class="position-relative">
                            
                            <!-- Etiquetas Flotantes (Formato) -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex gap-1">
                                <?php if(isset($libro['formato'])): ?>
                                    <?php if(strpos(strtolower($libro['formato']), 'pdf') !== false): ?>
                                        <span class="badge rounded-pill shadow-sm" style="background-color: #6f42c1;">PDF</span>
                                    <?php endif; ?>
                                    <?php if(strpos(strtolower($libro['formato']), 'digital') !== false || strpos(strtolower($libro['formato']), 'epub') !== false): ?>
                                        <span class="badge rounded-pill shadow-sm" style="background-color: #6f42c1;">EPUB</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Lógica del botón de favoritos (Convertido en botón circular flotante) -->
                            <?php if(isset($_SESSION['id_usuario'])): ?>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <?php if(in_array($libro['id_libro'], $favoritos_usuario)): ?>
                                        <!-- Corazón RELLENO (Ya es favorito) -->
                                        <a href="../acciones/toggle_favorito.php?id=<?= $libro['id_libro'] ?>&origen=catalogo" class="btn btn-light bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center text-danger" style="width: 35px; height: 35px;" title="Quitar de Favoritos">
                                            <i class="bi bi-heart-fill"></i>
                                        </a>
                                    <?php else: ?>
                                        <!-- Corazón VACÍO (No es favorito) -->
                                        <a href="../acciones/toggle_favorito.php?id=<?= $libro['id_libro'] ?>&origen=catalogo" class="btn btn-light bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center text-secondary" style="width: 35px; height: 35px;" title="Agregar a Favoritos">
                                            <i class="bi bi-heart"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Imagen de Portada -->
                            <?php 
                                // Si el libro no tiene URL en la BD, muestra una imagen por defecto
                                $portada = !empty($libro['imagen_portada']) ? $libro['imagen_portada'] : 'https://via.placeholder.com/300x450/e0e0e0/6f42c1?text=Sin+Portada'; 
                            ?>
                            <img src="<?= htmlspecialchars($portada) ?>" class="card-img-top" alt="Portada de <?= htmlspecialchars($libro['titulo']) ?>" style="height: 350px; object-fit: cover; border-bottom: 1px solid #f0f0f0;">
                        </div>

                        <!-- Cuerpo de la tarjeta -->
                        <div class="card-body d-flex flex-column text-center p-3">
                            <p class="text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px; color: #6f42c1;">
                                <?php echo htmlspecialchars($libro['genero']); ?>
                            </p>
                            <h5 class="card-title text-dark fw-bold mb-1" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">
                                <?php echo htmlspecialchars($libro['titulo']); ?>
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted small">
                                <?php echo htmlspecialchars($libro['autor']); ?>
                            </h6>
                            
                            <p class="card-text text-dark fw-bold fs-4 mt-2 mb-3">$<?php echo number_format($libro['precio'], 2); ?></p>
                            
                            <!-- Alertas de Inventario en tiempo real (Req 17 y 18) -->
                            <?php if ($libro['existencias'] == 0): ?>
                                <div class="alert alert-danger py-1 px-2 text-center small mb-3 fw-bold"><i class="bi bi-x-circle"></i> Agotado</div>
                            <?php elseif ($libro['existencias'] <= 5): ?>
                                <div class="alert alert-warning py-1 px-2 text-center small mb-3 fw-bold"><i class="bi bi-exclamation-triangle"></i> ¡Últimas <?php echo $libro['existencias']; ?> piezas!</div>
                            <?php else: ?>
                                <div class="alert alert-success py-1 px-2 text-center small mb-3"><i class="bi bi-check-circle"></i> Disponible: <?php echo $libro['existencias']; ?></div>
                            <?php endif; ?>

                            <div class="mt-auto d-grid gap-2">
                                <!-- Botón para ver la sinopsis, formato y calificaciones -->
                                <a href="detalle_libro.php?id=<?php echo $libro['id_libro']; ?>" class="btn btn-outline-primary btn-sm rounded-pill fw-bold" style="border-color: #6f42c1; color: #6f42c1;">
                                    <i class="bi bi-eye"></i> Ver Detalle
                                </a>
                                <!-- Botón para agregar al carrito -->
                                <a href="../acciones/procesar_compra.php?accion=agregar&id=<?php echo $libro['id_libro']; ?>" class="btn text-white btn-sm rounded-pill shadow-sm fw-bold" style="background-color: #6f42c1;">
                                    <i class="bi bi-cart-plus"></i> Agregar al Carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <p class="mt-3 text-muted fs-5">No se encontraron libros que coincidan con tu búsqueda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>