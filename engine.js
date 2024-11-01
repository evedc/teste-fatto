// JANELA EDITAR //

function openEditModal(id, nome, custo, data_limite) {
    document.getElementById('editId').value = id;
    document.getElementById('editNome').value = nome;
    document.getElementById('editCusto').value = custo;
    document.getElementById('editDataLimite').value = data_limite;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}