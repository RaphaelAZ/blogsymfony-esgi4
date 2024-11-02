document.addEventListener('DOMContentLoaded', function () {
    const timepicker = document.querySelector('.timepicker');

    timepicker.addEventListener('blur', function () {
        let value = this.value;
        if (value) {
            let [hours, minutes] = value.split(':').map(Number);
            minutes = Math.round(minutes / 10) * 10;

            if (minutes === 60) {
                hours += 1;
                minutes = 0;
            }

            hours = String(hours).padStart(2, '0');
            minutes = String(minutes).padStart(2, '0');

            this.value = `${hours}:${minutes}`;
        }
    });
});
