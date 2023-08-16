<div class="profile container-profile-p">
    <img src="../uploaded-img/clientes/<?= $fetch_profile['foto']; ?>" alt="" class="logo-img profile-p">
</div>
<h3>Actualizar Datos</h3>
<input type="hidden" name="old_img" value="<?= $fetch_profile['foto']; ?>">
<div class="input-field">
    <label for="name">Nombre Completo <sup>*</sup></label>
    <input type="text" name="name" maxlength="30" placeholder="Ingrese nombre completo" oninput="this.value.replace(/\s/g,'') " pattern="^[a-zA-Z ]+$" value="<?= $fetch_profile['nombre_Usuario']; ?>">
</div>


<div class="input-field">
    <label for="email"> Email <sup>*</sup></label>
    <input type="email" name="email" maxlength="25" placeholder="Ingrese su email" oninput="this.value.replace(/\s/g,'')" value="<?= $fetch_profile['email_Usuario']; ?>">
</div>
<div class="input-field">
    <label for="phone"> Telefono <sup>*</sup></label>
    <input type="number" name="phone" maxlength="10" placeholder="Ingrese su telefono" oninput="this.value.replace(/\s/g,'')" minlength="10" value="<?= $fetch_profile['telefono_Usuario'] ?>">
</div>
<div class="input-field">
    <label for="address">Direccion <sup>*</sup></label>
    <input type="text" name="address" maxlength="60" placeholder="Ingrese su Direccion" oninput="this.value.replace(/\s/g,'') " value="<?php if ($datosDir != null) {
                                                                                                                                            echo $datosDir['direccion'];
                                                                                                                                        } ?>" id="direccion">
</div>
<div class="input-field">
    <label for="barrio">Barrio <sup>*</sup></label>
    <input type="text" name="barrio" maxlength="60" placeholder="Ingrese su Barrio" oninput="this.value.replace(/\s/g,'') " value="<?php if ($datosDir != null) {
                                                                                                                                        echo $datosDir['barrio'];
                                                                                                                                    } ?>" id="barrio">
</div>
<div class="input-field">
    <label for="localidad">localidad <sup>*</sup></label>
    <input type="text" name="localidad" maxlength="60" placeholder="Ingrese su Localidad" oninput="this.value.replace(/\s/g,'') " value="<?php if ($datosDir != null) {
                                                                                                                                                echo $datosDir['localidad'];
                                                                                                                                            } ?>" id="localidad">
</div>


<div class="input-field">
    <label for="payment-method"> Metodo pago</label>
    <select name="payment-method" id="payment-method">

        <option selected value="">Anterior metodo de pago: <?= $fetch_profile['metodo_pago']; ?></option>
        <option value="nequi">nequi</option>
        <option value="efectivo">efectivo</option>
    </select>

</div>

<div class="input-field">
    <label for="profile-p">Imagen De Perfil</label>
    <input type="file" name="profile-p" accept=".png, .jpg, .jpeg" title="Debe insertar una imagen" value="<?= $fetch_profile['foto']; ?>">

</div>
