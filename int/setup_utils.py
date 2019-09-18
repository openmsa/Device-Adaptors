from setuptools.command.install_egg_info import install_egg_info
from glob import glob
from os import walk, path

dd = None

def set_dest(path):
    global dd
    dd = path

def map_dirs(src, x, y):
    return [ (dd+y.format(d.split(path.sep)[1]), glob(d+"/"+x))
                for d in glob(src+'/*') ]

def flat_dir(src, x, y):
    return [ (dd+y, glob(src+x)) ]

def deep_dir(src):
    return [ (dd+root, [path.join(root, f) for f in files])
		for root, dirs, files in walk(src) ]


class null_install_egg_info(install_egg_info):
    def run(self): pass
