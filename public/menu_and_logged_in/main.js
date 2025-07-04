document.addEventListener('DOMContentLoaded', () => {
    const links = {
        'fitnessLink': 'fitness',
        'aiTrainerLink': 'aitrial'
    };

    Object.keys(links).forEach(id => {
        const link = document.getElementById(id);
        if (link) {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                
                try {
                    const response = await fetch('folder_router.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ app: links[id] })
                    });
                    
                    const result = await response.json();
                    
                    if (result.status === 'success') {
                        window.location.href = result.redirect;
                    } else {
                        alert(result.message);
                        window.location.href = '../login_system/login.html';
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        }
    });
});