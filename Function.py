#Function.py

import redis
import os
import shutil
from _datetime import datetime


def install_redis():
    IsInstalled = "dpkg -l | grep redis-server"
    if not IsInstalled:
        os.system("apt install redis-server")
    else:
        print("Program already installed")


def TransportRedisDatabase():
    Time = str(datetime.now().strftime("%d-%m-%Y_%I-%M-%S_%p"))
    newFilename = "Backup_Redis_Database " + Time + ".rdb"
    print("test1")
    if os.path.isfile("/var/lib/redis/dump.rdb"):
        try:
            print("test2")
            os.rename("/var/lib/redis/dump.rdb", "/var/lib/redis/{}".format(newFilename))
        except FileExistsError:
            print("File does not exist")
    print("test3")
    try:
        os.system("scp wbakker@9.blaauwgeers.amsterdam:/data/latest/p2/redis/dump.rdb /tmp")
    except FileExistsError:
        print("Transfer failed")
    print("Transfer done")


def CopyRedisDatabase():
    DatabaseLocation = input("Please input the full path of the folder that contains Redis database: ")
    DatabaseSaveLocation = input("Enter path to store the database: ")
    try:
        for file in os.listdir(DatabaseLocation):
            try:
                if file.endswith(".rdb"):
                    os.system("cp {}".format(DatabaseLocation) + file + DatabaseSaveLocation)
                    # print(file)
                    print("File copied to ", DatabaseSaveLocation)
            except FileNotFoundError:
                print("No redis database found")

    except IsADirectoryError:
        print("Directory not found")
