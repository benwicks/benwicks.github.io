#!/usr/bin/env python
import random
import sha

fOut = open("keys.txt","w")
s = sha.new()
for i in range(0,100):
	s.update(str(random.getrandbits(256)))
	key = s.hexdigest()
	fOut.write(key+"\n")
fOut.close()
