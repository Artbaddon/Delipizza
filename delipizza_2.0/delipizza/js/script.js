const toggle = document.querySelector('.toggle-btn');
toggle.addEventListener('click', () => {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
})

const userBtn = document.querySelector('#user-btn');
userBtn.addEventListener('click', () => {
    const userBox = document.querySelector('.profile-detail');
    userBox.classList.toggle('active');
})	

