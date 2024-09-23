var tagline = document.querySelector('#tagline');
tagline.innerHTML = tagline.innerHTML.replace('{{phpExpYears}}', String(new Date().getFullYear() - 2014));

