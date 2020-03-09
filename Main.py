import redis
import Function

Function.install_redis()
#Function.TransportRedisDatabase()


try:
    conn = redis.Redis(
        host='9.blaauwgeers.amsterdam',
        port=6379,
        password='')
    #print(conn)
    conn.ping()
    print('Connected!')

except Exception as ex:
    print('Error:', ex)
    exit('Failed to connect, terminating.')


