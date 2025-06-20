const nodemailer = require('nodemailer');

const sendEmail = async (options) => {
  // Create SendGrid transporter
  const transporter = nodemailer.createTransport({
    host: 'smtp.sendgrid.net',
    port: 587,
    auth: {
      user: 'apikey', // Literally the word 'apikey'
      pass: process.env.EMAIL_PASSWORD // Your SendGrid API key
    }
  });

  // Email options
  const mailOptions = {
    from: `Your App <${process.env.EMAIL_FROM || 'noreply@yourdomain.com'}>`,
    to: options.email,
    subject: options.subject,
    text: options.message,
    // html: options.html (for HTML emails)
  };

  // Send email
  await transporter.sendMail(mailOptions);
};

module.exports = sendEmail;
