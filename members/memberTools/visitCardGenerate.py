#!/usr/bin/env python2
from __future__ import print_function
from PIL import Image, ImageDraw, ImageFont, ImageOps
# import Tkinter as tk
import sys

def line(position, content, draw):
    font = ImageFont.truetype('/opt/lampp/htdocs/members/memberTools/consola.ttf', size=42)
    color = 'rgb(255, 255, 255)'
    draw.text(position, content, fill=color, font=font)

def open_image(image_name):
    return Image.open(image_name)

def student(cname, fullname, email, mob, addr1, addr2, uid):

    blank_image = open_image("/opt/lampp/htdocs/members/memberTools/blankvc.png")
    draw = ImageDraw.Draw(blank_image)

    (x, y) = (98, 130)
    line((x, y), cname, draw)

    (x, y) = (98, 375)
    line((x, y), fullname, draw)

    (x, y) = (98, 425)
    line((x, y), email, draw)

    (x, y) = (98, 475)
    line((x, y), mob, draw)

    # (x, y) = (700, 475)
    # line((x, y), cwebsite, draw)

    (x, y) = (98, 575)
    line((x, y), addr1, draw)

    (x, y) = (98, 625)
    line((x, y), addr2, draw)

    saveName = "/opt/lampp/htdocs/members/memberResources/VCards/" + uid + ".png"
    blank_image.save(saveName)

    # (x, y) = (460, 452+62)
    # unique_id = 'ID'
    # line((x, y), unique_id, draw)
    
    #qr_data = first_name+unique_id+college+date_reg+time_reg
    # qr_data = person
    # qr_image(qr_data, (170, 860), blank_image)
    
    # (x, y) = (660, 334)
    # offset = (x, y)
    # logo = Image.open(filename, 'r')
    # background = Image.open(logo)
    # background.paste(logo, offset)
    # saveName = uid + ".png"
    # background.save(saveName)
    # line((x, y), logo, draw)


if __name__ == '__main__':
    # for i in sys.argv:
    #     print("test: " + i)
    
    student(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5], sys.argv[6], sys.argv[7])
    print("hello")