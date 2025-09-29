document.getElementById('module_id').addEventListener('change', function () {
    const disabled = this.value == 0;

    document.getElementById('submit_button').disabled = disabled;
    document.getElementById('extract_file').disabled = disabled;
});
