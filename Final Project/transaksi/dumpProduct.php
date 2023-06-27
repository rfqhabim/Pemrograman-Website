<?php include("../connections.php");
$query = "SELECT * FROM product";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table>
    <?php foreach ($result as $data):?>
        <form action="addToCart.php" method="post">
    <tr>
        <td><?php echo $data['ID_PRODUCT']; ?></td>
        <td><?php echo $data['NAME_PRODUCT']; ?></td>
        <td><?php echo $data['PRICE_PRODUCT']; ?></td>
        <td><button type="submit">Beli</button></td>
        <td><a href="addToCart.php?action=plus&id=<?php echo $data['ID_PRODUCT']; ?>&link=product">Tambah</a></td>
    </tr>
    <input type="hidden" name="id" value="<?php echo $data['ID_PRODUCT']; ?>">
    <input type="hidden" name="quantity" value="1">
    
    </form>
    <?php endforeach;?>
</table>