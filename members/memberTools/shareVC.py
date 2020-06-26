#!/usr/bin/env python2
import sys
import urllib
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders

fromAddress = '********@****.com'
toAddress = sys.argv[1]

cardSrc = sys.argv[2]
fromName = sys.argv[4]

msg = MIMEMultipart()
msg['From'] = "VA: WT-OEP"
msg['To'] = toAddress
msg['Subject'] = "Check out %s's Visiting Card" % fromName
body = "Attached, you will find my Visiting Card\n-%s\n\nGenerated @ VA" % fromName
msg.attach(MIMEText(body))

attachment = open(cardSrc, "rb")
p = MIMEBase('application', 'octet-stream')
p.set_payload((attachment).read())
encoders.encode_base64(p)
p.add_header('Content-Disposition', "attachment; filename=VCard.png")
msg.attach(p)

try:
	s = smtplib.SMTP('****.****.com:****')
	s.ehlo()
	s.starttls()
	s.login(fromAddress, sys.argv[3])
	text = msg.as_string()
	print text
	s.sendmail(fromAddress, toAddress, text)
	s.quit()

except smtplib.SMTPException:
	err = 1
