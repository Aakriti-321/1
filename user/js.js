function showForm(formId){
    document.getElementById('form-overlay').style.display = 'flex';
    document.getElementById('main-content').classList.add('blur');

    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

// blur
document.getElementById('form-overlay').addEventListener('click', function(e){
    if(e.target === this){
        this.style.display = 'none';
        document.getElementById('main-content').classList.remove('blur');
    }
});
