// Path to the images folder
const imagesFolder = "image";

// Array of image file names (you can manually list them or fetch them dynamically)
const imageFiles = [
    "gif1.gif",
    "gif2.gif",
    "gif3.gif",
    // Add more file names as needed
];

// Get the container where images will be inserted
const container = document.getElementById("gif-container");

// Loop through the image files and create <img> elements
imageFiles.forEach((file) => {
    const imgElement = document.createElement("gif");
    imgElement.src = imagesFolder + file; // Set the image source
    imgElement.alt = "GIF Image"; // Set alt text for accessibility
    container.appendChild(imgElement); // Add the image to the container
});