#!/usr/bin/env ruby

def crc32(buffer)
  crc = 0xFFFFFFFF

  buffer.each_byte do |value|
    crc = uint32(crc ^ (value << 24))
    8.times do
      if (crc & 0x80000000).nonzero?
        crc = uint32((crc << 1) ^ 0x04C11DB7)
      else
        crc = uint32(crc << 1)
      end
    end
  end

  return crc
end

def uint32(n)
  n % (1 << 32)
end

if ARGV.empty?
  puts "usage: #{$0} STRING"
  exit 1
end

printf("%08x\n", crc32(ARGV[0]))