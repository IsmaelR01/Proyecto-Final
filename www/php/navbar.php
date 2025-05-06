
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid d-flex align-items-center">
        <a href="index.php"><img src="imagenes/logo.png" class="navbar-logo ms-2 mt-2" alt="logo"></a>

        <div class="collapse navbar-collapse" id="barraNavegacion">
            <ul class="navbar-nav ms-auto">
                <li id="productos" class="nav-item ms-2 mt-2 position-relative"><a class="nav-link" href="jerseys.php">Productos</a>
                    <div id="submenu-productos" class="submenu-productos">
                        <a href="jerseys.php" class="d-block text-white px-3 py-1">Jerseys</a>
                        <a href="chaquetas.php" class="d-block text-white px-3 py-1">Chaquetas</a>
                    </div>
                </li>
                <li class="nav-item ms-2 mt-2"><a class="nav-link" href="quienesSomos.php">Quienes Somos</a></li>
                <li class="nav-item ms-2 mt-2"><a class="nav-link" href="contacto.php">Contacto</a></li>
            </ul>

        </div>

        <div class="ms-3 me-3 mt-2">
            <?php if (isset($_SESSION['usuario'])) { ?>
                <div class="dropdown">
                    <a class="btn btn-outline-light dropdown-toggle d-flex align-items-center" href="#" role="button" id="perfilDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="imagenes/emoticono.jpg" alt="Perfil" class="rounded-circle me-2" width="32" height="32">
                        <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="perfilDropdown">
                        <li><a class="dropdown-item text-danger" href="php/logout.php">Cerrar sesión</a></li>
                        <?php if($_SESSION['rol'] === 'admin') { ?>
                            <li><a class="dropdown-item" href="php/administrarUsuarios.php">Administrar Usuarios</a></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php  } else { ?>
                <a href="php/login.php">
                    <button type="button" class="btn btn-primary">Iniciar Sesión</button>
                </a>
            <?php } ?>
        </div>

        <button class="navbar-toggler ms-auto me-2" type="button" data-bs-toggle="collapse" data-bs-target="#barraNavegacion">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const productos = document.getElementById("productos");
        const submenu = document.getElementById("submenu-productos");

        productos.addEventListener("mouseover", () => {
            submenu.style.display = "block";
        });

        productos.addEventListener("mouseleave", () => {
            submenu.style.display = "none";
        });
    });
</script>


