    from redis.client import redis
    import os
    import shutil
    from _datetime import datetime
     
     
    def install_redis():
        IsInstalled = "dpkg -l | grep redis-server"
        if not IsInstalled:
            os.system("apt install redis-server")
        else:
            print("Program already installed")
     
     
    def BackupOrginalRedisDatabase():
        Time = str(datetime.now().strftime("%d-%m-%Y_%I-%M-%S_%p"))
        newFilename = "Backup_Redis_Database " + Time + ".rdb"
        move = "mv /var/lib/redis/* /home/wbakkerlocal/"
        try:
            if os.path.isfile('/var/lib/redis/dump.rdb'):
                os.system(move)
        except FileExistsError:
            print('File does not exist')
     
        try:
            os.rename("/home/wbakkerlocal/dump.rdb", newFilename)
        except FileNotFoundError:
            print('No such file or directory')
     
        if FileNotFoundError or FileExistsError:
            print("Could not backup database")
        else:
            print("Backing up current database")
     
     
    def CopyRedisDatabase():
        DatabaseLocation = input("Please input the full path of the folder that contains Redis database: ")
        DatabaseSaveLocation = input("Enter path to store the database: ")
        try:
            for file in os.listdir(DatabaseLocation):
                try:
                    if file.endswith(".rdb"):
                        os.system("cp {}".format(DatabaseLocation) + file + (DatabaseSaveLocation))
                        # print(file)
                        print("File copied to ", DatabaseSaveLocation)
                except FileNotFoundError:
                    print("No redis database found")
     
        except IsADirectoryError:
            print("Directory not found")
     
     
    def RedisDatabaseConn():
        try:
            conn = redis.StrictRedis(
                host='127.0.0.1',
                port=6379,
                password='')
            print(conn)
            conn.ping()
            print('Connected!')
        except Exception as ex:
            print('Error:', ex)
            exit('Failed to connect, terminating.')
     
     
    def GetRedisValues():
        for key in conn.scan_iter():
            print(key)
     
     
    RedisDatabaseConn()
    GetRedisValues()

